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
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FestivalArtistController extends AbstractController
{
    public function __construct(private readonly FestivalArtistRepository $festivalArtistRepository, private readonly EntityManagerInterface $entityManager){}

    #[Route('/public/festivalArtist', name: 'all_lineup', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
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

    #[Route('/public/festivalArtist/festival/{id}', name: 'show_festival_lineup', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function festivalLineup(int $id):Response{

        $festival = $this->festivalArtistRepository->findBy(['festival'=>$id]);
        if (!$festival) {
//            throw $this->createNotFoundException('Lineup not found.');
            return $this->render('error/error.html.twig', [
                'message' => 'Lineup not found',
                'go_back_to'=>'all_festivals',
                'name'=>'festivals'
            ]);
        }
        return $this->render('festival_artist/festivalLineup.html.twig', [
            'FestivalLineUp' => $festival
        ]);
    }

    #[Route('/public/festivalArtist/artist/{id}', name: 'show_festival_artist', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function showArtistSchedule(int $id):Response{
        $artist=$this->festivalArtistRepository->findBy(['artist_id'=>$id]);
        return $this->render('festival_artist/artistSchedule.html.twig', [
            'artistSchedule' => $artist
        ]);
    }

    #[Route('/admin/festivalArtist/delete/{id}', name:'delete_lineup', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/festivalArtist/new', name: 'new_lineup', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/festivalArtist/{id}/edit', name: 'edit_lineup', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
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
