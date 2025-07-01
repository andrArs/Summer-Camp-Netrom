<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PurchaseController extends AbstractController
{

    public function __construct(private readonly PurchaseRepository $purchaseRepository){}

    #[Route('/purchase', name: 'all_purchases', methods: ['GET'])]
    public function getAllPurchases(): Response
    {
        $purchases = $this->purchaseRepository->findAll();

        return $this->render('purchase/index.html.twig', [
            'purchases' => $purchases
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
}
