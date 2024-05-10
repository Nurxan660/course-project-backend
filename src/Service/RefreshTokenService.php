<?php

namespace App\Service;

use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RefreshTokenService
{
    public function __construct(private RefreshTokenManagerInterface $refreshTokenManager,
                                private RefreshTokenGeneratorInterface $refreshTokenGenerator,
                                private int $ttl)
    {
    }

    public function createRefreshToken(UserInterface $user): string {
        $refreshToken = $this->refreshTokenGenerator->createForUserWithTtl($user, $this->ttl);
        $refreshToken->setValid((new \DateTime())->modify("+{$this->ttl} seconds"));
        $refreshToken->setUsername($user->getUserIdentifier());
        $this->refreshTokenManager->save($refreshToken);
        return $refreshToken->getRefreshToken();
    }
}