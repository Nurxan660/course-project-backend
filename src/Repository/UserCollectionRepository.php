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
    private const SELECT_COLLECTION = 'c.id, c.name, c.description, c.imageUrl, ct.name AS categoryName,
            cf.name AS fieldName, cf.type AS fieldType, cf.isRequired AS fieldRequired, cf.showInTable';

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
        return $this->createQueryBuilder('c')
            ->select(SELF::SELECT_COLLECTION)
            ->leftJoin('c.customFields', 'cf')->leftJoin('c.category', 'ct')
            ->where('c.id = :id')
            ->setParameter('id', $collectionId)->getQuery()->getResult();
    }

    public function getLargestCollections(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name, COUNT(i.id) AS itemCount')
            ->leftJoin('c.items', 'i')
            ->groupBy('c.id')
            ->orderBy('itemCount', 'DESC')
            ->setMaxResults(5)->getQuery()->getResult();
    }

    public function getCollectionBasic(int $collectionId): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.name, c.description, c.imageUrl, ct.name AS categoryName')
            ->leftJoin('c.category', 'ct')
            ->where('c.id = :id')
            ->setParameter('id', $collectionId)->getQuery()->getResult();
    }
}
