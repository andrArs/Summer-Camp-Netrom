<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use App\Repository\UserDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class FestivalController extends AbstractController
{
    public function __construct(private readonly FestivalRepository $festivalRepository, private readonly EntityManagerInterface $entityManager){}


    #[Route('/festival/show/{id}', name: 'show_festival', methods: ['GET'])]
    public function getOneFestival(int $id): Response
    {
        $festival = $this->festivalRepository->find($id);
        if($festival === null){
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('festival/showFestival.html.twig', [
            'festival'=>$festival
        ]);
    }

    #[Route('/festival', name: 'all_festivals', methods: ['GET'])]
    public function getAllFestivals(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->festivalRepository->createQueryBuilder('f');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            3 //festivals per page
        );

        return $this->render('festival/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/festival/delete/{id}', name: 'delete_festival', methods: ['POST'])]
    public function deleteFestival(int $id): Response
    {
        $festival = $this->festivalRepository->find($id);

        if ($festival === null) {
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($festival);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_festivals');
    }

    #[Route('/festival/new', name: 'new_festival', methods: ['GET', 'POST'])]
    public function newFestival(Request $request): Response
    {
        $festival=new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($festival);
            $this->entityManager->flush();
            return $this->redirectToRoute('show_festival', ['id' => $festival->getId()]);

        }
        return $this->render('festival/newFestival.html.twig', [
            'form' => $form->createView(),
            'festival'=>$festival
        ]);
    }

    #[Route('/festival/{id}/edit', name: 'edit_festival', methods: ['GET', 'POST'])]
    public function editFestival(Request $request, int $id): Response
    {
        $festival = $this->festivalRepository->find($id);
        if($festival === null){
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('show_festival', ['id' => $festival->getId()]);
        }
        return $this->render('festival/editFestival.html.twig', [
            'form' => $form->createView(),
            'festival'=>$festival
        ]);
    }


}
