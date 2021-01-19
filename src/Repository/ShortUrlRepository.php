<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\Filter;
use App\Entity\ShortUrl;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Doctrine\ORM\QueryBuilder;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

    /**
     * @param DateTimeImmutable $createdAt
     * @return array{createdAt: DateTimeImmutable, urls: int}
     */
    public function findByCreationDate(DateTimeImmutable $createdAt): array
    {
        $query = $this->createQueryBuilder('su');
        $query->select('su.createdAt, COUNT(su.id) AS urls')
            ->andWhere($query->expr()->andX(
                $query->expr()->gte('su.createdAt', ':createdAt'),
                $query->expr()->lt('su.createdAt', ':createdAtTop'),
            ))->setParameters([
                'createdAt'    => $createdAt,
                'createdAtTop' => $createdAt->modify('+1 day'),
            ])
            ->groupBy('su.createdAt');

        return $query->getQuery()->getResult();
    }

    /**
     * @param Filter $filter
     * @return array{user: string, urls: int, lastCreated: string}
     */
    public function findGroupingByUserAndCreation(Filter $filter): array
    {
        $query = $this->createQueryBuilder('su');
        $query->select('u.id AS user, COUNT(su.user) AS urls, MAX(su.createdAt) AS lastCreated')
            ->join('su.user', 'u')
            ->groupBy('u.id');

        if ($filter->hasUserId()) {
            $query->having($query->expr()->eq('u.id', ':userID'))
                ->setParameter('userID', $filter->userId);
        }

        if ($filter->hasCreationDate()) {
            $query->andWhere($query->expr()->andX(
                $query->expr()->gte('su.createdAt', ':createdAt'),
                $query->expr()->lt('su.createdAt', ':createdAtTop'),
            ))
                ->setParameter('createdAt', $filter->createdAt)
                ->setParameter('createdAtTop', $filter->createdAt->modify('+1 day'));
        }

        return $query->getQuery()->getResult();
    }
}
