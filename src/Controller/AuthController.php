<?php

namespace App\Controller;

use App\DTO\RegRequest;
use App\Service\AuthService;
use App\Service\RefreshTokenService;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(private AuthService  $authService,
                                private JWTTokenManagerInterface $tokenManager,
                                private RefreshTokenGeneratorInterface $refreshTokenGenerator,
                                private RefreshTokenService  $refreshTokenService,)
    {
    }

    #[Route(path: "/register", name: 'register', methods: ['POST'])]
    public function register(RequestStack $requestStack): JsonResponse {
        $req = new RegRequest($requestStack);
        $user = $this->authService->register($req->getEmail(), $req->getPassword());
        $jwt = $this->tokenManager->create($user);
        $refreshToken = $this->refreshTokenService->createRefreshToken($user);
        return new JsonResponse(['token' => $jwt, 'refreshToken' => $refreshToken]);
    }
}