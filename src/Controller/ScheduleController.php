<?php

namespace App\Controller;

use App\Repository\ScheduleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ScheduleController extends AbstractController
{
    public function __construct(private readonly ScheduleRepository $scheduleRepository){}

    #[Route('/schedule', name: 'all_schedules', methods: ['GET'])]
    public function getAllSchedules(): Response
    {
        $schedules = $this->scheduleRepository->findAll();

        return $this->render('schedule/index.html.twig', [
            'schedules' => $schedules
        ]);
    }

    #[Route('/schedule/{id}', name: 'one_schedule', methods: ['GET'])]
    public function getOneSchedule(int $id): Response
    {
        $schedule = $this->scheduleRepository->find($id);
        if($schedule === null){
            return $this->json(['error' => 'Schedule not found'], Response::HTTP_NOT_FOUND);

        }
        return $this->render('schedule/index.html.twig', [
            'schedule' => $schedule
        ]);
    }
}
