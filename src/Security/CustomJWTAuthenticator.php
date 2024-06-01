<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\UserBlockedException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authenticator\JWTAuthenticator;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomJWTAuthenticator extends JWTAuthenticator
{
    /**
     * @throws UserBlockedException
     */
    protected function loadUser(array $payload, string $identity): UserInterface
    {
        $user = parent::loadUser($payload, $identity);
        if ($user instanceof User && $user->isBlocked()) {
            throw new UserBlockedException();
        }
        return $user;
    }

}