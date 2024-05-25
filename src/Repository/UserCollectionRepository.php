<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserCollection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserCollection>
 */
class UserCollectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserCollection::class);
    }

    public function getCollectionsByUser(User $user): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('c')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->orderBy('c.id', 'ASC');
    }

    public function getCollection(int $collectionId): array
    {
        $query = $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.description, c.imageUrl, ct.name as categoryName,
            cf.name as fieldName, cf.type AS fieldType, cf.isRequired AS fieldRequired')
            ->leftJoin('c.customFields', 'cf')->leftJoin('c.category', 'ct')
            ->where('c.id = :id')->setParameter('id', $collectionId);
        return $query->getQuery()->getArrayResult();
    }
}
