<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('admin@gmail.com');
        $user1->setPassword('adminpass123');
        $user1->setToken('token123abc456');
        $user1->setRole('ROLE_ADMIN');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('user1@gmail.com');
        $user2->setPassword('user1pass789');
        $user2->setToken('token789xyz123');
        $user2->setRole('ROLE_USER');
        $manager->persist($user2);

        $user3 = new User();
        $user3->setEmail('user2@gmail.com');
        $user3->setPassword('passuser2abc');
        $user3->setToken('token456def789');
        $user3->setRole('ROLE_USER');
        $manager->persist($user3);

        $manager->flush();
    }
}
