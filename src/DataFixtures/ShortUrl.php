<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ShortUrl extends Fixture implements DependentFixtureInterface
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function load(ObjectManager $manager): void
    {
        $existedUsers = $this->users->findAll(); // just 12 @see \App\DataFixtures\User::load

        for ($i=0; $i<5554; $i++) {
            $fakeDomain = base64_encode(random_bytes(11));
            $fakeExt = base64_encode(random_bytes(3));

            $randUser = $existedUsers[array_rand($existedUsers)];
            $shortUrl = new \App\Entity\ShortUrl($randUser, "http://{$fakeDomain}.{$fakeExt}");
            $manager->persist($shortUrl);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            User::class
        ];
    }
}
