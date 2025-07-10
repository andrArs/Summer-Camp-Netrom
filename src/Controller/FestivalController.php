<?php

namespace App\Controller;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use App\Repository\UserDetailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class FestivalController extends AbstractController
{
    public function __construct(private readonly FestivalRepository $festivalRepository, private readonly EntityManagerInterface $entityManager){}


    #[Route('/public/festival/show/{id}', name: 'show_festival', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getOneFestival(int $id): Response
    {
        $festival = $this->festivalRepository->find($id);
        if($festival === null){
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }

        $today = new \DateTime();
        $startDate = $festival->getStartDate();
        $endDate = $festival->getEndDate();

        if ($today < $startDate) {
            $status = 'upcoming';
            $daysLeft = $today->diff($startDate)->format('%r%a');
        } elseif ($today >= $startDate && $today <= $endDate) {
            $status = 'ongoing';
            $daysLeft = 0;
        } else {
            $status = 'past';
            $daysLeft = 0;
        }


        return $this->render('festival/showFestival.html.twig', [
            'festival'=>$festival,
            'daysLeft'=>$daysLeft,
            'status'=>$status
        ]);
    }

    #[Route('/public/festival', name: 'all_festivals', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getAllFestivals(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->festivalRepository->createQueryBuilder('f');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6 //festivals per page
        );

        return $this->render('festival/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/admin/festival/delete/{id}', name: 'delete_festival', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/festival/new', name: 'new_festival', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newFestival(Request $request): Response
    {
        $festival=new Festival();
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);
        if ($form->isSubmitted() ) {
            if ($festival->getStartDate() > $festival->getEndDate()) {
                $this->addFlash('error', 'Start date must be before end date.');
                return $this->redirectToRoute('new_festival');

            }
            if($form->isValid()){
            $this->entityManager->persist($festival);
            $this->entityManager->flush();
            $this->addFlash('success', 'Festival created successfully.');
            return $this->redirectToRoute('show_festival', ['id' => $festival->getId()]);}

        }
        return $this->render('festival/newFestival.html.twig', [
            'form' => $form->createView(),
            'festival'=>$festival
        ]);
    }

    #[Route('/admin/festival/{id}/edit', name: 'edit_festival', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editFestival(Request $request, int $id): Response
    {
        $festival = $this->festivalRepository->find($id);
        if($festival === null){
            return $this->json(['error' => 'Festival not found'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(FestivalType::class, $festival);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if($festival->getStartDate() > $festival->getEndDate()){
                $this->addFlash('error', 'Start date must be before end date.');
                return $this->redirectToRoute('edit_festival', ['id' => $festival->getId()]);
            }
            if($form->isValid()) {
                $this->entityManager->flush();
                $this->addFlash('success', 'Festival updated successfully.');
                return $this->redirectToRoute('show_festival', ['id' => $festival->getId()]);
            }
        }
        return $this->render('festival/editFestival.html.twig', [
            'form' => $form->createView(),
            'festival'=>$festival
        ]);
    }


}
