<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PurchaseController extends AbstractController
{

    public function __construct(private readonly PurchaseRepository $purchaseRepository,  private readonly EntityManagerInterface $entityManager){}

    #[Route('/admin/purchase', name: 'all_purchases', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/purchase/show/{id}', name: 'show_purchase', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/purchase/delete/{id}', name:'delete_purchase', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
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

    #[Route('/admin/purchase/new', name: 'new_purchase', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
public function newPurchase(Request $request): Response
    {
        $purchase = new Purchase();
        $form = $this->createForm(PurchaseType::class, $purchase);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();
            return $this->redirectToRoute('show_purchase',['id' => $purchase->getId()]);
        }
        return $this->render('purchase/new.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView()
        ]);
    }
}
