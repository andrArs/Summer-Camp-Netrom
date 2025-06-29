<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    public function __construct(private readonly ArtistRepository $artistRepository){}
    #[Route('/artist', name: 'all_artists',methods: ['GET'])]
    public function getAllArtists(): Response
    {
        $artists = $this->artistRepository->findAll();

        return $this->render('artist/index.html.twig', [
            'artists' =>$artists
        ]);



}

    #[Route('/artist/{id}', name: 'one_artist',methods: ['GET'])]
    public function getOneArtist(int $id): Response
    {
        $artist = $this->artistRepository->find($id);

        if($artist === null){
            return $this->json(['error' => 'Artist not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->render('artist/oneArtist.html.twig', [
            'artist' =>$artist
        ]);
    }
}
