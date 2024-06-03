<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    public function findByUserAndItemId(int $itemId, ?string $email): ?Like
    {
        return $this->createQueryBuilder('l')
            ->select('l')
            ->leftJoin('l.item', 'i')
            ->leftJoin('l.user', 'u')
            ->where('i.id = :itemId')->andWhere('u.email = :userEmail')
            ->setParameter('itemId', $itemId)->setParameter('userEmail', $email)
            ->getQuery()->getOneOrNullResult();
    }
}
