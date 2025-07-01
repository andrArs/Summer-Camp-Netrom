<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    #[Route('/user/{id}', name: 'show_user', methods: ['GET'])]
    public function getOneUser(int $id): Response
    {
        $user = $this->userRepository->find($id);
        if($user === null){
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}', name: 'delete_user', methods: ['POST'])]
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
}
