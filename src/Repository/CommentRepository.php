<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Media;
use App\Enum\CommentStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findValidatedByMedia(Media $media): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.media = :media')
            ->andWhere('c.status = :status')
            ->setParameter('media', $media)
            ->setParameter('status', CommentStatusEnum::VALIDATED)
            ->orderBy('c.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
