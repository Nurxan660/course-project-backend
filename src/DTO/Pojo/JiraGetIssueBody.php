<?php

namespace App\DTO\Pojo;

use App\Enum\PaginationLimit;

class JiraGetIssueBody
{
    private string $jql;
    private int $startAt;
    private int $maxResults;
    private array $fields;

    public function __construct(string $jql, int $startAt = 0)
    {
        $this->jql = $jql;
        $this->startAt = $startAt;
        $this->maxResults = PaginationLimit::DEFAULT->value;
        $this->fields = ['status'];
    }

    public function getJql(): string
    {
        return $this->jql;
    }

    public function getStartAt(): int
    {
        return $this->startAt;
    }

    public function getMaxResults(): int
    {
        return $this->maxResults;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}