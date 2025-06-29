<?php

namespace App\DataFixtures;

use App\Entity\Stage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $stages = [
            ['Main Stage', 'The primary stage hosting headline acts across genres', 'Mixed'],
            ['Electro Dome', 'Indoor stage for electronic music lovers', 'Electronic'],
            ['Hip Hop Hub', 'Dedicated to hip hop and rap artists', 'Hip Hop'],
            ['Jazz Corner', 'Smooth jazz performances and jam sessions', 'Jazz'],
            ['Metal Pit', 'Heavy metal and hard rock stage', 'Metal'],
            ['Pop Plaza', 'Popular hits and chart-topping artists', 'Pop'],
        ];

        foreach ($stages as [$name, $description, $genre]) {
            $stage = new Stage();
            $stage->setName($name);
            $stage->setDescription($description);
            $stage->setGenre($genre);
            $manager->persist($stage);
        }

        $manager->flush();
    }
}
