<?php

namespace App\Service\Mapper;

use App\DTO\CollectionResponse;
use App\Entity\UserCollection;

class CollectionMapper
{
    public function mapToDto(UserCollection $collection): CollectionResponse
    {
        return new CollectionResponse($collection->getId(),
            $collection->getName(), $collection->getDescription(),
            $collection->getImageUrl(), $collection->getCategory()->getName()
        );
    }
}