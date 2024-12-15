<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Movie>
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    public function findBySearch(string $search): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.title LIKE :search')
            ->setParameter('search', '%'.$search.'%')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findRecommended(int $limit = 3): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.releaseDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function search(string $query): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.title LIKE :query')
            ->orWhere('m.shortDescription LIKE :query')
            ->orWhere('m.longDescription LIKE :query')
            ->setParameter('query', '%'.$query.'%')
            ->orderBy('m.releaseDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
