<?php

namespace App\Service\Mapper;

use App\DTO\CollectionDTO\CollectionEditRes;
use App\DTO\CollectionDTO\CollectionPaginationRes;
use App\DTO\CollectionDTO\CollectionRes;
use App\DTO\CustomField;
use App\Entity\UserCollection;
use App\Enum\PaginationLimit;

class CollectionMapper
{
    public function mapToCollection(UserCollection $collection): CollectionRes
    {
        return new CollectionRes($collection->getId(),
            $collection->getName(), $collection->getCategory()->getName());
    }

    public function mapToCollectionBasic(array $collectionArray): CollectionRes
    {
        $c = $collectionArray[0];
        return new CollectionRes($c['id'],
            $c['name'], $c['categoryName'], $c['description'], $c['imageUrl']);
    }

    public function mapToEditCollectionDto(array $query): CollectionEditRes
    {
        return new CollectionEditRes(
            $query[0]['id'], $query[0]['name'],
            $query[0]['description'], $query[0]['imageUrl'],
            $query[0]['categoryName'], $query[0]['isPublic']
        );
    }

    public function mapToCollectionCustomField(array $query, CollectionEditRes $collectionEditRes): CollectionEditRes
    {
        foreach ($query as $row) {
            if (isset($row['fieldName'], $row['fieldType'], $row['fieldRequired'])) {
                $customFieldDTO = new CustomField($row['fieldName'],
                    $row['fieldType'], $row['fieldRequired'], $row['showInTable']);
                $collectionEditRes->addCustomField($customFieldDTO);
            }
        }
        return $collectionEditRes;
    }

    public function mapToPaginationRes(array $collections, int $totalPages): CollectionPaginationRes
    {
        $paginationResponse = new CollectionPaginationRes();
        $paginationResponse->setTotalPages($totalPages);
        $paginationResponse->setLimit(PaginationLimit::DEFAULT->value);
        $paginationResponse->setCollections($collections);
        return $paginationResponse;
    }
}