<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ArtistController extends AbstractController
{
    public function __construct(private readonly ArtistRepository $artistRepository, private readonly EntityManagerInterface $entityManager){}
//    #[Route('/artist', name: 'all_artists',methods: ['GET'])]
//    public function getAllArtists(): Response
//    {
//        $artists = $this->artistRepository->findAll();
//
//        return $this->render('artist/index.html.twig', [
//            'artists' =>$artists
//        ]);
//}
    #[Route('/artist', name: 'all_artists', methods: ['GET'])]
    public function getAllArtists(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->artistRepository->createQueryBuilder('a');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            9 //artists per page
        );

        return $this->render('artist/index.html.twig', [
            'pagination' => $pagination
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

    #[Route('/artist/{id}', name: 'delete_artist', methods: ['POST'])]
    public function deleteArtist(int $id): Response
    {
        $artist = $this->artistRepository->find($id);

        if ($artist === null) {
            return $this->json(['error' => 'Artist not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($artist);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_artists');
    }


}
