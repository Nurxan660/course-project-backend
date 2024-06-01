<?php

namespace App\DTO\UserDTO;

use App\Enum\Role;

class UserChangeRoleReq
{
    private array $ids;
    private Role $userRole;

    public function getIds(): array
    {
        return $this->ids;
    }

    public function setIds(array $ids): void
    {
        $this->ids = $ids;
    }

    public function getUserRole(): Role
    {
        return $this->userRole;
    }

    public function setUserRole(string $userRole): void
    {
        $this->userRole = Role::from($userRole);
    }
}