<?php

namespace App\Service;

use App\Entity\User;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\QueryString;
use Elastica\Query\Terms;
use Elastica\Query\Term;

class SearchService
{
    public function __construct()
    {
    }

    public function getSearchQuery(string $searchTerm, array $searchFields): Query
    {
        $queryString = new QueryString();
        $queryString->setFields($searchFields)
            ->setQuery($searchTerm . '*')
            ->setDefaultOperator('AND');
        return new Query($queryString);
    }

    public function getDeleteQueryByUserId(array $ids): Query
    {
        $termsQuery = new Terms('collection.user.id', $ids);
        return new Query($termsQuery);
    }

    public function buildBoolQueryForUserAndIds(string $termsId, string $termId, array $ids, User $user): BoolQuery
    {
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Terms($termsId, $ids))
            ->addMust(new Term([$termId => $user->getId()]));
        return $boolQuery;
    }
}