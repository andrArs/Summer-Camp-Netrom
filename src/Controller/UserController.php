<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserDetails;
use App\Form\EditUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class UserController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/admin/app/user', name: 'all_users', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/app/user/show/{id}', name: 'show_user', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/app/user/delete/{id}', name: 'delete_user', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/app/user/new', name: 'new_user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newUser(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= new User();
        $userDetails = new UserDetails();

        $userDetails->setUserId($user);
        $user->setDetails($userDetails);

        $form=$this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $form->getErrors(true,false);

            $plainPassword = $form->get('password')->getData();

            // Hash the password
            $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);

            $this->entityManager->persist($user);
            $this->entityManager->persist($userDetails);
            $this->entityManager->flush();
            return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
        }
        return $this->render('user/newUser.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
        ]);
    }

    #[Route('/admin/app/user/{id}/edit', name: 'edit_user', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editUser(Request $request, int $id): Response
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);

        }
        $userDetails =$user->getDetails();

        $form=$this->createForm(EditUserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (empty($user->getPassword()))
                $user->setPassword($user->getPassword());


           $this->entityManager->flush();
            return $this->redirectToRoute('show_user', ['id' => $user->getId()]);
        }
        return $this->render('user/editUser.html.twig', [
            'form' => $form->createView(),
            'user'=>$user,
        ]);
    }

    #[Route('/user/app/user/purchases', name: 'user_purchases', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userPurchases(): Response
    {
     $user = $this->getUser();
     $purchases = $user->getPurchases();

        return $this->render('user/userPurchases.html.twig', [
         'purchases' => $purchases

     ]);
    }

    #[Route('/user/app/user/profile', name: 'user_profile', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function userProfile(): Response
    {
        $user = $this->getUser();
        return $this->render('user/userProfile.html.twig', [
            'user' => $user
        ]);


    }



}
