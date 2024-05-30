<?php

namespace App\Service;

use App\Service\Mapper\ItemMapper;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\Serializer\SerializerInterface;

class SearchService
{
    public function __construct(private TransformedFinder $finder,
                                private SerializerInterface $serializer,
                                private ItemMapper $itemMapper)
    {
    }

    public function searchItems(string $searchTerm): array {
        $query = $this->getSearchItemQuery($searchTerm);
        $results = $this->finder->findRaw($query);
        return $this->itemMapper->mapToSearchItemResponseDto($results);
    }

    public function getSearchItemQuery(string $searchTerm): Query {
        return new Query([
            'query' => [
                'query_string' => [
                    'default_field' => '*',
                    'query' => $searchTerm . '*',
                    'default_operator' => 'AND'

                ]
            ]
        ]);
    }
}