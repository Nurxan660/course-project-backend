<?php

namespace App\Service\Mapper;

use App\DTO\CollectionPaginationRes;
use App\DTO\CollectionRes;
use App\Entity\UserCollection;
use App\Enum\PaginationLimit;

class CollectionMapper
{
    public function mapToCollection(UserCollection $collection): CollectionRes
    {
        return new CollectionRes($collection->getId(),
            $collection->getName(), $collection->getDescription());
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