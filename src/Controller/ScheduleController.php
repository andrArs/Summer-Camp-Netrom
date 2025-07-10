<?php

namespace App\Controller;

use App\Entity\Schedule;
use App\Form\ScheduleType;
use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ScheduleController extends AbstractController
{
    public function __construct(private readonly ScheduleRepository $scheduleRepository,  private readonly EntityManagerInterface $entityManager){}

    #[Route('/public/schedule', name: 'all_schedules', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getAllSchedules(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->scheduleRepository->createQueryBuilder('s');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            3
        );

        return $this->render('schedule/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/public/schedule/show/{id}', name: 'show_schedule', methods: ['GET'])]
    #[IsGranted('PUBLIC_ACCESS')]
    public function getOneSchedule(int $id): Response
    {
        $schedule = $this->scheduleRepository->find($id);
        if($schedule === null){
            return $this->json(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);

        }
        return $this->render('schedule/show.html.twig', [
            'schedule' => $schedule
        ]);
    }

    #[Route('/admin/schedule/delete/{id}', name:'delete_schedule', methods: ['POST'] )]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteSchedule(int $id): Response
    {
        $schedule = $this->scheduleRepository->find($id);
        if($schedule === null){
            return $this->json(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($schedule);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_schedules');
    }

    #[Route('/admin/schedule/new', name:'new_schedule', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function newSchedule(Request $request): Response
    {
        $schedule= new Schedule();
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);
        if($form->isSubmitted() ){
            if($schedule->getStartTime()>$schedule->getEndTime()){
                $this->addFlash('error', 'Start time must be before end time');
                return $this->redirectToRoute('new_schedule');
            }
            if($form->isValid()){
            $this->entityManager->persist($schedule);
            $this->entityManager->flush();

            $this->addFlash('success', 'Schedule created successfully');
            return $this->redirectToRoute('edit_schedule', ['id' => $schedule->getId()]);
        }
        }
        return $this->render('schedule/new.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView()
        ]);
    }

    #[Route('/admin/schedule/edit/{id}', name:'edit_schedule', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function editSchedule(Request $request, int $id): Response
    {
        $schedule = $this->scheduleRepository->find($id);
        if($schedule === null){
            return $this->json(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);
        }
        $form = $this->createForm(ScheduleType::class, $schedule);
        $form->handleRequest($request);
        if($form->isSubmitted() ){
            if($schedule->getStartTime()>$schedule->getEndTime()){
                $this->addFlash('error', 'Start time must be before end time');
                return $this->redirectToRoute('edit_schedule', ['id' => $schedule->getId()]);            }

            if($form->isValid()){
            $this->entityManager->flush();

            $this->addFlash('success', 'Schedule updated successfully');
            return $this->redirectToRoute('edit_schedule', ['id' => $schedule->getId()]);
            }

        }
        return $this->render('schedule/edit.html.twig', [
            'schedule' => $schedule,
            'form' => $form->createView()

        ]);
    }

}
