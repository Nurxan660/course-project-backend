<?php

namespace App\DTO\UserDTO;

class UserBlockRequest
{
    private bool $status;
    private array $ids;

    public function __construct(bool $status, array $ids)
    {
        $this->status = $status;
        $this->ids = $ids;
    }

    public function isStatus(): bool
    {
        return $this->status;
    }

    public function getIds(): array
    {
        return $this->ids;
    }
}