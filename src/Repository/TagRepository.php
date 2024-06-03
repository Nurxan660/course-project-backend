<?php

namespace App\Repository;

use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tag>
 */
class TagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }

    public function getPopularTags(): array
    {
        return $this->createQueryBuilder('tag')
            ->select('tag.name AS value, COUNT(item.id) AS count')
            ->leftJoin('tag.items', 'item')
            ->groupBy('tag.name')
            ->orderBy('count', 'DESC')
            ->setMaxResults(50)->getQuery()->getResult();
    }
}
