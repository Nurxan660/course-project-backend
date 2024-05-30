<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findAllUsers(): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('u')
            ->select('u.id, u.email, u.fullName, u.registerDate, u.role')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
    }

    public function changeUserLockedStatus(array $ids, bool $status)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.isBlocked', ':status')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)->setParameter('status', $status)
            ->getQuery()->execute();
    }
}
