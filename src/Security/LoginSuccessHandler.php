<?php

namespace App\Security;

use App\Entity\User;
use App\Exception\UserBlockedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private AuthenticationSuccessHandlerInterface $authenticationSuccessHandler)
    {
    }

    /**
     * @throws UserBlockedException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $user = $token->getUser();
        $fullName = $user instanceof User ? $user->getFullName() : "";
        $user->isBlocked() ? throw new UserBlockedException() : '';
        $response = $this->authenticationSuccessHandler->onAuthenticationSuccess($request, $token);
        return $this->changeResponse($response, $fullName, $user->getRole()->value);
    }

    private function changeResponse(Response $response, string $fullName, string $role): Response {
        $data = json_decode($response->getContent(), true);
        $data['fullName'] = $fullName;
        $data['role'] = $role;
        $response->setContent(json_encode($data));
        return $response;
    }
}