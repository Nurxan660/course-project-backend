<?php

namespace App\Service\Mapper;

use App\DTO\ItemDTO\SearchItemResponse;
use App\DTO\SearchTagResponse;
use Elastica\Result;

class TagMapper
{
    public function mapToSearchTagResponseDto(array $results): array
    {
        return array_map(function (Result $result) {
            $source = $result->getSource();
            return new SearchTagResponse($source['name']);
        }, $results);
    }
}