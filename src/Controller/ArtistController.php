<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\DBAL\Types\TextType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
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
        $searchTerm = $request->query->get('search', '');
        $queryBuilder = $this->artistRepository->createQueryBuilder('a');

        if (!empty($searchTerm)) {
            $queryBuilder
                ->where('a.name LIKE :search')
                ->setParameter('search', '%' . $searchTerm . '%');
        }

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6 //artists per page
        );

        return $this->render('artist/index.html.twig', [
            'pagination' => $pagination,
            'search' => $searchTerm
        ]);
    }

    #[Route('/artist/show/{id}', name: 'one_artist',methods: ['GET'])]
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

    #[Route('/artist/delete/{id}', name: 'delete_artist', methods: ['POST'])]
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


#[Route('/artist/new', name: 'new_artist', methods: ['GET', 'POST'])]
public function newArtist(Request $request): Response
{
    $artist=new Artist();
    $form = $this->createForm(ArtistType::class, $artist);

    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
        $artist=$form->getData();

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_artists');

    }
    return $this->render('artist/newArtist.html.twig', [
        'form' => $form->createView(),
        'artist'=> $artist

    ]);


}

#[Route('/artist/{id}/edit', name: 'edit_artist', methods: ['GET', 'POST'])]
public function editArtist(Request $request, int $id): Response
{
    $artist = $this->artistRepository->find($id);
    if($artist === null){
        return $this->json(['error' => 'Artist not found'], Response::HTTP_NOT_FOUND);
    }
    $form = $this->createForm(ArtistType::class, $artist);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){

        $artist=$form->getData();

        $this->entityManager->persist($artist);
        $this->entityManager->flush();

        return $this->redirectToRoute('one_artist', [
            'id' => $artist->getId()
        ]);

    }
    return $this->render('artist/editArtist.html.twig', [
        'form' => $form->createView(),
        'artist'=> $artist

    ]);
}



}
