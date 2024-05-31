<?php

namespace App\Service;

use App\Enum\PaginationLimit;
use Elastica\Query;

class SearchService
{
    public function __construct()
    {
    }

    public function getSearchQuery(string $searchTerm): Query {
        return new Query([
            'query' => [
                'query_string' => ['default_field' => '*', 'query' => $searchTerm . '*', 'default_operator' => 'AND']
            ],
            'size' => PaginationLimit::TAGS
        ]);
    }
}