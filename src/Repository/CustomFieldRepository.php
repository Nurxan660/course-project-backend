<?php

namespace App\Repository;

use App\Entity\CustomField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomField>
 */
class CustomFieldRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomField::class);
    }

    public function deleteCustomFields(array $names) {
        $query = $this->createQueryBuilder('c')
            ->delete(CustomField::class,'c')
            ->where('c.name IN (:names)')
            ->setParameter('names', $names);
        return $query->getQuery()->execute();
    }
}
