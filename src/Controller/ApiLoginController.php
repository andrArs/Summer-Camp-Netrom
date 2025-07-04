<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function index(#[CurrentUser] ?User $user): Response
    {
        if (null === $user) {
            return $this->json([
            'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
         }

        $token ='smth'; // somehow create an API token for $user
  return $this->json([
      'user'  => $user->getUserIdentifier(),
      'token' => $token,
  ]);
   }
}
