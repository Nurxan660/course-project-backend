<?php

namespace App\Service;

use App\Enum\PaginationLimit;
use Elastica\Client;
use Elastica\Query;

class SearchService
{
    public function __construct(private Client $client)
    {
    }

    public function getSearchQuery(string $searchTerm): Query
    {
        return new Query([
            'query' => [
                'query_string' => ['default_field' => '*', 'query' => $searchTerm . '*', 'default_operator' => 'AND']
            ],
            'size' => PaginationLimit::TAGS
        ]);
    }

    public function getDeleteQueryByUserName(array $ids): array
    {
        return [
            'query' => [
                'terms' => [
                    'collection.user.id' => $ids
                ]
            ]
        ];
    }
    public function deleteUsersFromElasticsearch(array $userIds): void
    {
        $index = $this->client->getIndex('items');
        $deleteQuery = $this->getDeleteQueryByUserName($userIds);
        $index->deleteByQuery($deleteQuery);
    }
}