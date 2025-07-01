<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseController extends AbstractController
{

    public function __construct(private readonly PurchaseRepository $purchaseRepository,  private readonly EntityManagerInterface $entityManager){}

    #[Route('/purchase', name: 'all_purchases', methods: ['GET'])]
    public function getAllPurchases(Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $this->purchaseRepository->createQueryBuilder('p');


        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('purchase/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/purchase/{id}', name: 'show_purchase', methods: ['GET'])]
    public function getOnePurchase(int $id): Response
    {
        $purchase = $this->purchaseRepository->find($id);

        if($purchase === null){
            return $this->json(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('purchase/show.html.twig', [
            'purchase' => $purchase
        ]);
    }

    #[Route('purchase/{id}', name:'delete_purchase', methods: ['POST'])]
    public function deletePurchase(int $id): Response
    {
        $purchase = $this->purchaseRepository->find($id);
        if($purchase === null){
            return $this->json(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }
        $this->entityManager->remove($purchase);
        $this->entityManager->flush();

        return $this->redirectToRoute('all_purchases');
    }
}
