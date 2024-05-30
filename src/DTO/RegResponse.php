<?php

namespace App\DTO;

class RegResponse implements \JsonSerializable
{
    private string $token;
    private string $refreshToken;
    private string $fullName;
    private string $role;

    public function __construct(string $token, string $refreshToken, string $fullName, string $role)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
        $this->fullName = $fullName;
        $this->role = $role;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->getToken(),
            'refreshToken' => $this->getRefreshToken(),
            'fullName' => $this->getFullName(),
            'role' => $this->getRole()
        ];
    }

    public function getRole(): string
    {
        return $this->role;
    }
}