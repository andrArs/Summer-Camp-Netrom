<?php

namespace App\Controller;

use App\Entity\FestivalArtist;
use App\Form\LineupType;
use App\Repository\FestivalArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalArtistController extends AbstractController
{
    public function __construct(private readonly FestivalArtistRepository $festivalArtistRepository, private readonly EntityManagerInterface $entityManager){}

    #[Route('/festivalArtist', name: 'all_lineup', methods: ['GET'])]
    public function getAll(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->festivalArtistRepository->createQueryBuilder('a');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('festival_artist/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/festivalArtist/festival/{id}', name: 'show_festival_lineup', methods: ['GET'])]
    public function festivalLineup(int $id):Response{

        $festival = $this->festivalArtistRepository->findBy(['festival'=>$id]);
        return $this->render('festival_artist/festivalLineup.html.twig', [
            'FestivalLineUp' => $festival
        ]);
    }

    #[Route('/festivalArtist/artist/{id}', name: 'show_festival_artist', methods: ['GET'])]
    public function showArtistSchedule(int $id):Response{
        $artist=$this->festivalArtistRepository->findBy(['artist_id'=>$id]);
        return $this->render('festival_artist/artistSchedule.html.twig', [
            'artistSchedule' => $artist
        ]);
    }

    #[Route('/festivalArtist/delete/{id}', name:'delete_lineup', methods: ['POST'])]
public function deleteLineup(int $id): Response
    {
        $fa = $this->festivalArtistRepository->find($id);

        if ($fa === null) {
            return $this->json(['error' => 'Lineup not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($fa);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_lineup');
    }

    #[Route('/festivalArtist/new', name: 'new_lineup', methods: ['GET', 'POST'])]
    public function newLineup(Request $request): Response{
        $festivalArtist = new FestivalArtist();
        $form=$this->createForm(LineupType::class,$festivalArtist);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($festivalArtist);
            $this->entityManager->flush();
            return $this->redirectToRoute('all_lineup', ['id' => $festivalArtist->getId()]);

        }
        return $this->render('festival_artist/newLineup.html.twig', [
            'form' => $form->createView(),
            'lineup' => $festivalArtist
        ]);
    }

    #[Route('/festivalArtist/{id}/edit', name: 'edit_lineup', methods: ['GET', 'POST'])]
    public function editArtistFestival(Request $request, int $id): Response
    {
        $lineup = $this->festivalArtistRepository->find($id);
        if($lineup === null){
            return $this->json(['error' => 'Lineup not found'], Response::HTTP_NOT_FOUND);

        }
        $form=$this->createForm(LineupType::class,$lineup);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();
            return $this->redirectToRoute('all_lineup', ['id' => $lineup->getId()]);
        }
        return $this->render('festival_artist/editLineup.html.twig', [
            'form' => $form->createView(),
            'lineup' => $lineup
        ]);
    }

}
