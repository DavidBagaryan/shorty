<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO;
use App\Entity\ShortUrl;
use App\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ShortUrlKeeper
{
    private EntityManagerInterface $em;
    private Repository\UserRepository $users;
    private Repository\ShortUrlRepository $shortUrls;

    public function __construct(
        EntityManagerInterface $em,
        Repository\UserRepository $users,
        Repository\ShortUrlRepository $shortUrls
    ) {
        $this->em = $em;
        $this->users = $users;
        $this->shortUrls = $shortUrls;
    }

    public function getById($hashUrl): ShortUrl
    {
        $url = $this->shortUrls->findOneBy(['value' => $hashUrl]);
        if (null === $url) {
            throw new EntityNotFoundException("url not found");
        }
        return $url;
    }

    public function new(string $userId, string $original): ShortUrl
    {
        $existed = $this->shortUrls->findOneBy(['original' => $original]);
        if (null !== $existed) {
            return $existed;
        }

        $url = ShortUrl::fromString($this->users->find($userId), $original);
        $this->em->persist($url);
        $this->em->flush();
        return $url;
    }

    public function list(): array
    {
        return array_map(
            static fn(ShortUrl $url) => DTO\Url::fromShortUrlEntity($url),
            $this->shortUrls->findAll()
        );
    }

    /**
     * @param DTO\Filter $filter
     * @return DTO\CreatingDateWithUrl[]
     */
    public function stats(DTO\Filter $filter): array
    {
        if ($filter->hasOnlyCreationDate()) {
            return array_map(
                static fn(array $stats) => new DTO\CreatingDateWithUrl($stats['createdAt'], $stats['urls']),
                $this->shortUrls->findByCreationDate($filter->createdAt)
            );
        }

        return array_map(
            static fn(array $stats) => new DTO\UserWithUrls($stats['user'], $stats['urls'], $stats['lastCreated']),
            $this->shortUrls->findGroupingByUserAndCreation($filter)
        );
    }
}
