<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
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
