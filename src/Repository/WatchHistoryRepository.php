<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\WatchHistory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WatchHistory>
 */
class WatchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WatchHistory::class);
    }

    public function findByUser(User $user, int $limit = 3): array
    {
        return $this->createQueryBuilder('w')
            ->where('w.watcher = :user')
            ->setParameter('user', $user)
            ->orderBy('w.lastWatchedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
