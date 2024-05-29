<?php

namespace App\Service;

use App\DTO\RegResponse;
use App\Entity\User;
use App\Enum\Role;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher,
                                private EntityManagerInterface $entityManager,
                                private JWTTokenManagerInterface $tokenManager,
                                private RefreshTokenService  $refreshTokenService,
                                private UserRepository $userRepository,)
    {
    }

    public function handleRegister(string $email, string $password): RegResponse
    {
        $user = $this->setUser($email, $password);
        $this->saveUser($user);
        return $this->createToken($user);
    }

    /**
     * @throws UserNotFoundException
     */

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

    private function createToken(User $user): RegResponse
    {
        $jwt = $this->tokenManager->create($user);
        $refreshToken = $this->refreshTokenService->createRefreshToken($user);
        return new RegResponse($jwt, $refreshToken, $user->getEmail());
    }
}