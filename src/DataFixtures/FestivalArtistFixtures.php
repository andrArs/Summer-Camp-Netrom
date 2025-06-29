<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use App\Entity\Festival;
use App\Entity\FestivalArtist;
use App\Entity\Schedule;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class FestivalArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $festivalRepo = $manager->getRepository(Festival::class);
        $artistRepo = $manager->getRepository(Artist::class);
        $scheduleRepo = $manager->getRepository(Schedule::class);

        $festival1 = $festivalRepo->findOneBy(['name' => 'Untold']);
        $festival2 = $festivalRepo->findOneBy(['name' => 'Neversea']);

        $artist1 = $artistRepo->findOneBy(['name' => 'INNA']);
        $artist2 = $artistRepo->findOneBy(['name' => 'The Weeknd']);
        $artist3 = $artistRepo->findOneBy(['name' => 'Coldplay']);

        $schedule1 = $scheduleRepo->findOneBy(['id' => 1]);
        $schedule2 = $scheduleRepo->findOneBy(['id' => 2]);
        $schedule3 = $scheduleRepo->findOneBy(['id' => 3]);

        $fa1 = new FestivalArtist();
        $fa1->setFestival($festival1);
        $fa1->setArtist($artist1);
        $fa1->setSchedule($schedule1);
        $manager->persist($fa1);

        $fa2 = new FestivalArtist();
        $fa2->setFestival($festival1);
        $fa2->setArtist($artist2);
        $fa2->setSchedule($schedule2);
        $manager->persist($fa2);

        $fa3 = new FestivalArtist();
        $fa3->setFestival($festival2);
        $fa3->setArtist($artist3);
        $fa3->setSchedule($schedule3);
        $manager->persist($fa3);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FestivalFixtures::class,
            ArtistFixtures::class,
            ScheduleFixtures::class,
        ];
    }
}
