<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/user', name: 'all_users', methods: ['GET'])]
    public function getAllUsers(Request $request,PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->userRepository->createQueryBuilder('u');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9
        );

        return $this->render('user/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/user/show/{id}', name: 'show_user', methods: ['GET'])]
    public function getOneUser(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('user/showUser.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/delete/{id}', name: 'delete_user', methods: ['POST'])]
public function deleteUser(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_users');
    }

    #[Route('/user/new', name: 'new_user', methods: ['GET', 'POST'])]
    public function newUser(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= new User();
        $userDetails = new UserDetails();

        $userDetails->setUserId($user);
        $user->setDetails($userDetails);

        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $plainPassword = $form->get('password')->getData();

            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->persist($userDetails);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_login');
        }
        return $this->render('user/newUser.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
        ]);
    }

    #[Route('/user/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    public function editUser(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);

        }
        $userDetails =$user->getDetails();

        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($user->getPassword()))
                $user->setPassword($user->getPassword());


           $this->entityManager->flush();
            return $this->redirectToRoute('all_users');
        }
        return $this->render('user/editUser.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
        ]);
    }
}
