<?php

namespace App\Service;

use App\Entity\User;
use App\Enum\Role;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher,
                                private EntityManagerInterface $entityManager,
                                private JWTTokenManagerInterface $tokenManager,
                                private RefreshTokenService  $refreshTokenService)
    {
    }

    public function handleRegister(string $email, string $password): User
    {
        $user = $this->setUser($email, $password);
        $this->saveUser($user);
        return $this->createToken($user);
    }

    private function setUser(string $email, string $password): User
    {
        $user = new User($email, Role::USER);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);
        return $user;
    }

    private function saveUser(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function createToken(User $user): array
    {
        $jwt = $this->tokenManager->create($user);
        $refreshToken = $this->refreshTokenService->createRefreshToken($user);
        return ['token' => $jwt, 'refreshToken' => $refreshToken];
    }
}