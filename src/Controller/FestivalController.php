<?php

namespace App\Controller;

use App\Repository\FestivalRepository;
use App\Repository\UserDetailsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    public function __construct(private readonly FestivalRepository $festivalRepository){}


    #[Route('/festival/{id}', name: 'one_festival', methods: ['GET'])]
    public function getOneFestival(int $id): Response
    {
        $festival = $this->festivalRepository->find($id);
        if($festival === null){
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('festival/oneFestival.html.twig', [
            'festival_id' =>$festival->getId(),
            'festival_name' => $festival->getName(),
            'festival_location' => $festival->getLocation(),
            'festival_start_date' => $festival->getStartDate(),
            'festival_end_date' => $festival->getEndDate()

        ]);
    }

    #[Route('/festival', name: 'all_festivals', methods: ['GET'])]
    public function getAllFestivals():Response
    {
        $festivals = $this->festivalRepository->findAll();
        return $this->render('festival/index.html.twig', [
            'festivals' => $festivals,
        ]);
    }
}
