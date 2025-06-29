<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use App\Entity\Purchase;
use App\Entity\Ticket;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PurchaseFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userRepo = $manager->getRepository(User::class);
        $festivalRepo = $manager->getRepository(Festival::class);
        $ticketRepo = $manager->getRepository(Ticket::class);

        $user1 = $userRepo->findOneBy(['email' => 'user1@gmail.com']);
        $user2 = $userRepo->findOneBy(['email' => 'user2@gmail.com']);

        $festival1 = $festivalRepo->findOneBy(['name' => 'Untold']);
        $festival2 = $festivalRepo->findOneBy(['name' => 'Neversea']);

        $ticket1 = $ticketRepo->findOneBy(['type' => 'General Access']);
        $ticket2 = $ticketRepo->findOneBy(['type' => 'VIP Pass']);

        $purchase1 = new Purchase();
        $purchase1->setUser($user1);
        $purchase1->setFestival($festival1);
        $purchase1->setTicket($ticket1);
        $manager->persist($purchase1);

        $purchase2 = new Purchase();
        $purchase2->setUser($user2);
        $purchase2->setFestival($festival2);
        $purchase2->setTicket($ticket2);
        $manager->persist($purchase2);

        $purchase3 = new Purchase();
        $purchase3->setUser($user1);
        $purchase3->setFestival($festival2);
        $purchase3->setTicket($ticket1);
        $manager->persist($purchase3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            FestivalFixtures::class,
            TicketFixtures::class,
        ];
    }
}
