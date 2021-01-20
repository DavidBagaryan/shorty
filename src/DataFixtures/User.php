<?php

declare(strict_types=1);

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class User extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // general users
        for ($i=0; $i<12; $i++) {
            $user = new \App\Entity\User('tokenized');
            $user->addAuthToken();
            $user->addAuthToken(); // for example

            $manager->persist($user);
        }
        $manager->flush();
    }
}
