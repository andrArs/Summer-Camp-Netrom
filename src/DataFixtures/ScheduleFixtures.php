<?php

namespace App\DataFixtures;

use App\Entity\Schedule;
use App\Entity\Stage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ScheduleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $stageRepository = $manager->getRepository(Stage::class);

        $mainStage = $stageRepository->findOneBy(['name' => 'Main Stage']);
        $electroStage = $stageRepository->findOneBy(['name' => 'Electro Dome']);
        $acousticStage = $stageRepository->findOneBy(['name' => 'Pop Plaza']);

        $schedule1 = new Schedule();
        $schedule1->setDay(1)
        ->setDate(new \DateTime('2025-08-01'))
            ->setStartTime(new \DateTime('12:00'))
            ->setEndTime(new \DateTime('14:00'))
            ->setStage($mainStage);

        $manager->persist($schedule1);

        $schedule2 = new Schedule();
        $schedule2->setDay(1)
        ->setDate(new \DateTime('2025-08-01'))
            ->setStartTime(new \DateTime('15:00'))
            ->setEndTime(new \DateTime('17:00'))
            ->setStage($electroStage);

        $manager->persist($schedule2);

        $schedule3 = new Schedule();
        $schedule3->setDay(2)
        ->setDate(new \DateTime('2025-08-02'))
            ->setStartTime(new \DateTime('13:00'))
            ->setEndTime(new \DateTime('15:30'))
            ->setStage($acousticStage);

        $manager->persist($schedule3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            StageFixtures::class,
        ];
    }
}
