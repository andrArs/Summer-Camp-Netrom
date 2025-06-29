<?php

namespace App\DataFixtures;

use App\Entity\Artist;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArtistFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $artists = [
            ['INNA', 'Dance'],
            ['Smiley', 'Pop'],
            ['Delia', 'Pop'],
            ['Carla’s Dreams', 'Pop/Rock'],
            ['Irina Rimes', 'Pop'],
            ['Subcarpați', 'Electro/Folk'],
            ['The Motans', 'Pop'],
            ['Antonia', 'Pop'],
            ['Alina Eremia', 'Pop'],
            ['Connect-R', 'Hip-Hop'],
            ['Puya', 'Hip-Hop'],
            ['Ștefan Bănică Jr.', 'Rock'],
            ['Cargo', 'Rock'],
            ['VUNK', 'Pop Rock'],
            ['BUG Mafia', 'Hip-Hop'],

            ['The Weeknd', 'R&B'],
            ['Dua Lipa', 'Pop'],
            ['Ed Sheeran', 'Pop/Folk'],
            ['Billie Eilish', 'Indie Pop'],
            ['Travis Scott', 'Hip-Hop'],
            ['Rihanna', 'R&B/Pop'],
            ['Coldplay', 'Alternative Rock'],
            ['David Guetta', 'EDM'],
            ['Imagine Dragons', 'Alternative Rock'],
            ['Beyoncé', 'Pop/R&B'],
        ];

        foreach ($artists as [$name, $genre]) {
            $artist = new Artist();
            $artist->setName($name);
            $artist->setGenre($genre);
            $manager->persist($artist);
        }

        $manager->flush();
    }
}
