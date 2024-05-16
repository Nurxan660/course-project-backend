<?php

namespace App\Security;

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
        $email = $token->getUser()->getUserIdentifier();
        return $this->changeResponse($response, $email);
    }

    private function changeResponse(Response $response, string $email): Response {
        $data = json_decode($response->getContent(), true);
        $data['email'] = $email;
        $response->setContent(json_encode($data));
        return $response;
    }
}