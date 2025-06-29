<?php

namespace App\DataFixtures;

use App\Entity\Ticket;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TicketFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tickets = [
            ['General Access', 'Standard entry for all festival days', 350],
            ['VIP Pass', 'VIP access with dedicated area and private bar', 550],
            ['Day Pass', 'Access for a single day of your choice', 150],
            ['Under 25', 'Discounted ticket for people under 25', 250],
            ['Underage Pass', 'Ticket for attendees under 18 with limited access', 230],
        ];

        foreach ($tickets as [$type, $description, $price]) {
            $ticket = new Ticket();
            $ticket->setType($type);
            $ticket->setDescription($description);
            $ticket->setPrice($price);
            $manager->persist($ticket);
        }

        $manager->flush();
    }
}
