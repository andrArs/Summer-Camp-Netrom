<?php

namespace App\Controller;

use App\Repository\FestivalArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalArtistController extends AbstractController
{
    public function __construct(private readonly FestivalArtistRepository $festivalArtistRepository){}

    #[Route('/festivalArtist', name: 'app_festival_artist', methods: ['GET'])]
    public function getAll(): Response
    {
        $festivalArtists = $this->festivalArtistRepository->findAll();
        return $this->render('festival_artist/index.html.twig', [
            'lineUp' => $festivalArtists
        ]);
    }
}
