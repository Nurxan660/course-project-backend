<?php

namespace App\DTO\JiraDTO;

class CreateJiraUserRes
{
    private string $accountId;

    public function __construct(string $accountId)
    {
        $this->accountId = $accountId;
    }

    public function getAccountId(): string
    {
        return $this->accountId;
    }

    public function setAccountId(string $accountId): void
    {
        $this->accountId = $accountId;
    }
}