<?php

namespace App\DTO\UserDTO;

class UserListResponse
{
    private int $totalItems;
    private array $users;

    public function __construct(int $totalItems, array $users)
    {
        $this->totalItems = $totalItems;
        $this->users = $users;
    }

    public function getTotalItems(): int
    {
        return $this->totalItems;
    }

    public function getUsers(): array
    {
        return $this->users;
    }
}