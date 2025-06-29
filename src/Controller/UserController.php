<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    #[Route('/user', name: 'all_users', methods: ['GET'])]
    public function getAllUsers(): Response
    {
        $users = $this->userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/{id}', name: 'one_user', methods: ['GET'])]
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
}
