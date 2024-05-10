<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher,
                                private EntityManagerInterface      $entityManager)
    {
    }

    public function register(string $email, string $password): User
    {
        $user = $this->setUser($email, $password);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    private function setUser(string $email, string $password): User
    {
        $user = new User($email, Role::USER);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        return $user;
    }
}