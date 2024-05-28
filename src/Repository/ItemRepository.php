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
    private const SELECT_ITEMS_WITH_CUSTOM_FIELDS = "cf.name AS fieldName, cf.showInTable as show, 
    cc.name AS categoryName, icf.value AS value";

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findItemsWithCustomFields(int $collectionId): \Doctrine\ORM\Query
    {
        return $this->createQueryBuilder('i')
            ->select('i.name, i.id')->addSelect(SELF::SELECT_ITEMS_WITH_CUSTOM_FIELDS)
            ->leftJoin('i.itemCustomFields', 'icf')
            ->leftJoin('icf.customField', 'cf')
            ->leftJoin('i.collection', 'c')->leftJoin('c.category', 'cc')
            ->where('i.collection = :collectionId')
            ->setParameter('collectionId', $collectionId)->orderBy('i.id', 'ASC')->getQuery();
    }

    public function deleteByIds(array $ids): void
    {
        $qb = $this->createQueryBuilder('i');
        $qb->delete(Item::class, 'i')
            ->where('i.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->execute();
    }

    public function getCustomFieldsWithValues(int $itemId): array
    {
        return $this->createQueryBuilder('i')
            ->select('i.name AS itemName, cf.name, icf.value, t.name AS tagName')
            ->leftJoin('i.tags', 't')
            ->leftJoin('i.itemCustomFields', 'icf')
            ->leftJoin('icf.customField', 'cf')
            ->where('i.id = :id')
            ->setParameter('id', $itemId)->getQuery()->getArrayResult();
    }

    public function findItemById(int $itemId): Item
    {
        return $this->createQueryBuilder('i')
            ->select('i, icf, t, cf')
            ->leftJoin('i.itemCustomFields', 'icf')
            ->leftJoin('i.tags', 't')
            ->leftJoin('icf.customField', 'cf')
            ->where('i.id = :id')
            ->setParameter('id', $itemId)->getQuery()->getOneOrNullResult();
    }
}
