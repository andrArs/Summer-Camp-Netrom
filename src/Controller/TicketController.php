<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketController extends AbstractController
{
    public function __construct(private readonly TicketRepository $ticketRepository){}

    #[Route('/ticket', name: 'all_tickets', methods: ['GET'])]
    public function getAllTickets(): Response
    {
        $tickets = $this->ticketRepository->findAll();
        return $this->render('ticket/index.html.twig', [
            'tickets' => $tickets
        ]);
    }
    #[Route('/ticket/{id}', name: 'one_ticket', methods: ['GET'])]
    public function getOneTicket(int $id): Response
    {
        $ticket = $this->ticketRepository->find($id);
        if($ticket === null){
            return $this->json(['error' => 'Ticket not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('ticket/one_ticket.html.twig', [
            'ticket' => $ticket
        ]);
    }
}
