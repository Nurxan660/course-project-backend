<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\RequestStack;

class RegRequest
{
    private string $email;
    private string $password;

    public function __construct(RequestStack $requestStack)
    {
        $content = $requestStack->getCurrentRequest()->getContent();
        $data = json_decode($content, true);
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}