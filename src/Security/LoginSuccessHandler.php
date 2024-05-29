<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    public function __construct(private AuthenticationSuccessHandlerInterface $authenticationSuccessHandler)
    {
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): Response
    {
        $response = $this->authenticationSuccessHandler->onAuthenticationSuccess($request, $token);
        $user = $token->getUser();
        $fullName = $user instanceof User ? $user->getFullName() : "";
        return $this->changeResponse($response, $fullName);
    }

    private function changeResponse(Response $response, string $fullName): Response {
        $data = json_decode($response->getContent(), true);
        $data['fullName'] = $fullName;
        $response->setContent(json_encode($data));
        return $response;
    }
}