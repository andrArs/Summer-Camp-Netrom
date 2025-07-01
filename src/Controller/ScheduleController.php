<?php

namespace App\Controller;

use App\Repository\ScheduleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleController extends AbstractController
{
    public function __construct(private readonly ScheduleRepository $scheduleRepository,  private readonly EntityManagerInterface $entityManager){}

    #[Route('/schedule', name: 'all_schedules', methods: ['GET'])]
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

    #[Route('/schedule/{id}', name: 'show_schedule', methods: ['GET'])]
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

    #[Route('/schedule/{id}', name:'delete_schedule', methods: ['POST'] )]
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
}
