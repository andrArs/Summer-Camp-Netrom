<?php

namespace App\Controller;

use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TicketController extends AbstractController
{
    public function __construct(private readonly TicketRepository $ticketRepository,  private readonly EntityManagerInterface $entityManager){}

    #[Route('/ticket', name: 'all_tickets', methods: ['GET'])]
    public function getAllTickets(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->ticketRepository->createQueryBuilder('t');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('ticket/index.html.twig', [
            'pagination' => $pagination
        ]);
    }
    #[Route('/ticket/{id}', name: 'show_ticket', methods: ['GET'])]
    public function getOneTicket(int $id): Response
    {
        $ticket = $this->ticketRepository->find($id);
        if($ticket === null){
            return $this->json(['error' => 'Ticket not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket
        ]);
    }

    #[Route('/ticket/{id}', name:'delete_ticket', methods: ['POST'] )]
    public function deleteTicket(int $id): Response
    {
        $ticket = $this->ticketRepository->find($id);
        if($ticket === null){
            return $this->json(['error' => 'Ticket not found'], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($ticket);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_tickets');
    }

}
