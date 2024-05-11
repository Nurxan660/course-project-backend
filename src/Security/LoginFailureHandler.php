<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;


class LoginFailureHandler implements AuthenticationFailureHandlerInterface
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): JsonResponse
    {
        $message = $this->translator->trans('invalid_credentials', [], 'api_errors');
        $data = [
            'message' => $message
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}