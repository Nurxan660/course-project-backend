<?php

namespace App\DTO;

class RegResponse implements \JsonSerializable
{
    private string $token;
    private string $refreshToken;
    private string $email;

    public function __construct(string $token, string $refreshToken, string $email)
    {
        $this->token = $token;
        $this->refreshToken = $refreshToken;
        $this->email = $email;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function jsonSerialize(): array
    {
        return [
            'token' => $this->getToken(),
            'refreshToken' => $this->getRefreshToken(),
            'email' => $this->getEmail()
        ];
    }
}