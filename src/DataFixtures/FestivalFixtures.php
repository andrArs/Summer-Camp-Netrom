<?php

namespace App\DataFixtures;

use App\Entity\Festival;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FestivalFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $festivals = [
            ['Untold', 'Cluj-Napoca, Romania', '2025-08-01', '2025-08-04'],
            ['Neversea', 'Constanța, Romania', '2025-07-04', '2025-07-07'],
            ['Electric Castle', 'Bonțida, Romania', '2025-07-17', '2025-07-21'],
            ['Summer Well', 'Buftea, Romania', '2025-08-09', '2025-08-11'],
            ['Nostalgia', 'București, Romania', '2025-06-14', '2025-06-16'],
            ['Saga Festival', 'București, Romania', '2025-06-06', '2025-06-08'],
            ['Flight Festival', 'Timișoara, Romania', '2025-09-06', '2025-09-08'],
            ['Codru Festival', 'Timișoara, Romania', '2025-09-12', '2025-09-17']
        ];

        foreach ($festivals as [$name, $location, $startDate, $endDate]) {
            $festival = new Festival();
            $festival->setName($name);
            $festival->setLocation($location);
            $festival->setStartDate(new \DateTime($startDate));
            $festival->setEndDate(new \DateTime($endDate));
            $manager->persist($festival);
        }

        $manager->flush();
    }
}
