<?php

namespace App\Controller;

use App\Entity\Purchase;
use App\Form\BuyTicketType;
use App\Form\PurchaseType;
use App\Repository\PurchaseRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class PurchaseController extends AbstractController
{

    public function __construct(private readonly PurchaseRepository $purchaseRepository, private readonly UserRepository $userRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

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

        if ($purchase === null) {
            return $this->json(['error' => 'Purchase not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->render('purchase/show.html.twig', [
            'purchase' => $purchase
        ]);
    }

    #[Route('/admin/purchase/delete/{id}', name: 'delete_purchase', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePurchase(int $id): Response
    {
        $purchase = $this->purchaseRepository->find($id);
        if ($purchase === null) {
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
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();
            return $this->redirectToRoute('show_purchase', ['id' => $purchase->getId()]);
        }
        return $this->render('purchase/new.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView()
        ]);
    }

    #[Route('/user/purchase/buy', name: 'buy_ticket_by_user_id', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function buy_ticket_by_user_id(Request $request): Response
    {
        $purchase = new Purchase();
        $user = $this->getUser();
        if ($user === null) {
            return $this->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        $purchase->setUser($user);

        $form = $this->createForm(BuyTicketType::class, $purchase);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($purchase);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_homepage', ['id' => $purchase->getId()]);
        }
        return $this->render('purchase/show_boughtTicket.html.twig', [
            'purchase' => $purchase,
            'form' => $form->createView(),
            'user' => $user
        ]);

    }

//    #[Route('/user/purchase', name: 'all_purchases_per_user', methods: ['GET'])]
//    #[IsGranted('ROLE_USER')]
//    public function getAllUserPurchases(Request $request, PaginatorInterface $paginator): Response
//    {
//        $purchases = $this->purchaseRepository;
//        $allUserPurchases=[];
//        foreach($purchases as $purchase){
//            if($purchase->getUser() == $this->getUser()){
//                $allPurchases=$purchase;
//            }
//            }
//
//
//        $query = $allPurchases->createQueryBuilder('p');
//        $pagination = $paginator->paginate(
//            $queryBuilder,
//            $request->query->getInt('page', 1),
//            6
//        );
//
//        return $this->render('purchase/index.html.twig', [
//            'pagination' => $pagination
//        ]);
//    }
}


