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
            ->select('u.id, u.email, u.fullName, u.registerDate, u.role, u.isBlocked')
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

    public function deleteUsers(array $ids)
    {
        return $this->createQueryBuilder('u')
            ->delete()
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()->execute();
    }

    public function changeUserRole(array $ids, string $role)
    {
        return $this->createQueryBuilder('u')
            ->update()
            ->set('u.role', ':role')
            ->where('u.id IN (:ids)')
            ->setParameter('ids', $ids)->setParameter('role', $role)
            ->getQuery()->execute();
    }
}
