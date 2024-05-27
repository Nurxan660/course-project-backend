<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    private const SELECT_ITEMS_WITH_CUSTOM_FIELDS = "cf.name AS fieldName, c.name AS collectionName, c.description, 
            c.imageUrl, cc.name AS categoryName, icf.value AS value";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findItemsWithCustomFields(int $collectionId): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('i')
            ->select('i.name')
            ->addSelect(SELF::SELECT_ITEMS_WITH_CUSTOM_FIELDS)
            ->leftJoin('i.itemCustomFields', 'icf')->leftJoin('icf.customField', 'cf')
            ->leftJoin('i.collection', 'c')->leftJoin('c.category', 'cc')
            ->where('i.collection = :collectionId')
            ->andWhere('cf.showInTable = true')
            ->setParameter('collectionId', $collectionId)
            ->orderBy('i.id', 'ASC')->getQuery();
    }
}
