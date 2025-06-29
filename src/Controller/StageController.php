<?php

namespace App\Controller;

use App\Repository\StageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class StageController extends AbstractController
{
    public function __construct(private readonly StageRepository $stageRepository){}

    #[Route('/stage', name: 'all_stages', methods: ['GET']) ]
    public function getAllStages(): Response
    {
        $stages = $this->stageRepository->findAll();
        return $this->render('stage/index.html.twig', [
            'stages' => $stages,
        ]);
    }

    #[Route('/stage/{id}', name: 'single_stage', methods: ['GET'])]
    public function getSingleStage(int $id): Response
    {
        $stage = $this->stageRepository->find($id);
        if($stage === null){
            return $this->json(['error' => 'Stage not found'], Response::HTTP_NOT_FOUND);

        }
        return $this->render('stage/single.html.twig', [
            'stage' => $stage
        ]);
    }

}
