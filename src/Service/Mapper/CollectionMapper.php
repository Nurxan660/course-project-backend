<?php

namespace App\Service\Mapper;

use App\DTO\CollectionEditRes;
use App\DTO\CollectionPaginationRes;
use App\DTO\CollectionRes;
use App\DTO\CustomField;
use App\Entity\UserCollection;
use App\Enum\PaginationLimit;

class CollectionMapper
{
    public function mapToCollection(UserCollection $collection): CollectionRes
    {
        return new CollectionRes($collection->getId(),
            $collection->getName(), $collection->getDescription());
    }

    public function mapToEditCollectionDto(array $query): CollectionEditRes
    {
        return new CollectionEditRes(
            $query[0]['id'], $query[0]['name'],
            $query[0]['description'], $query[0]['imageUrl'],
            $query[0]['categoryName']
        );
    }

    public function mapToCollectionCustomField(array $query, CollectionEditRes $collectionEditRes): CollectionEditRes
    {
        foreach ($query as $row) {
            if (isset($row['fieldName'], $row['fieldType'], $row['fieldRequired'])) {
                $customFieldDTO = new CustomField($row['fieldName'], $row['fieldType'], $row['fieldRequired']);
                $collectionEditRes->addCustomField($customFieldDTO);
            }
        }
        return $collectionEditRes;
    }

    public function mapToPaginationRes(array $collections, int $totalPages): CollectionPaginationRes
    {
        $paginationResponse = new CollectionPaginationRes();
        $paginationResponse->setTotalPages($totalPages);
        $paginationResponse->setLimit(PaginationLimit::COLLECTION->value);
        $paginationResponse->setCollections($collections);
        return $paginationResponse;
    }
}