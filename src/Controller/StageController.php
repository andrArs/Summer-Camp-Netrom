<?php

namespace App\Controller;

use App\Entity\Stage;
use App\Form\StageType;
use App\Repository\StageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class StageController extends AbstractController
{
    public function __construct(private readonly StageRepository $stageRepository, private readonly EntityManagerInterface $entityManager){}

    #[Route('/public/stage', name: 'all_stages', methods: ['GET']) ]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getAllStages(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->stageRepository->createQueryBuilder('s');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('stage/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/public/stage/show/{id}', name: 'show_stage', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getSingleStage(int $id): Response
    {
        $stage = $this->stageRepository->find($id);
        if($stage === null){
            return $this->json(['error' => 'Stage not found'], Response::HTTP_NOT_FOUND);

        }
        return $this->render('stage/show.html.twig', [
            'stage' => $stage
        ]);
    }

    #[Route('/admin/stage/delete/{id}', name:'delete_stage', methods: ['POST'] )]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteStage(int $id): Response
    {
        $stage = $this->stageRepository->find($id);
        if($stage === null){
            return $this->json(['error' => 'Stage not found'], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($stage);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_stages');
    }

    #[Route('/admin/stage/{id}/edit', name:'edit_stage', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editStage(Request $request, int $id): Response
    {
        $stage=$this->stageRepository->find($id);
        if($stage === null){
            return $this->json(['error' => 'Stage not found'], Response::HTTP_NOT_FOUND);

        }
        $form=$this->createForm(StageType::class, $stage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('show_stage', [
                'id' => $stage->getId()
            ]);

        }
        return $this->render('stage/edit.html.twig', [
            'form' => $form->createView(),
            'stage' => $stage
        ]);
    }

    #[Route('/admin/stage/new', name:'new_stage', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function addStage(Request $request): Response
    {
        $stage=new Stage();
        $form=$this->createForm(StageType::class, $stage);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($stage);
            $this->entityManager->flush();
            return $this->redirectToRoute('all_stages');

        }
        return $this->render('stage/new.html.twig', [
            'form' => $form->createView(),
            'stage' => $stage
        ]);
    }

}
