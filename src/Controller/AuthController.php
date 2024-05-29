<?php

namespace App\Controller;

use App\DTO\RegRequest;
use App\Exception\UserNotFoundException;
use App\Service\AuthService;
use App\Service\RefreshTokenService;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthController extends AbstractController
{
    public function __construct(private AuthService  $authService,
                                private JWTTokenManagerInterface $tokenManager,
                                private RefreshTokenGeneratorInterface $refreshTokenGenerator,
                                private RefreshTokenService  $refreshTokenService,
                                private ValidatorInterface $validator)
    {
    }

    /**
     * @throws UserNotFoundException
     */
    #[Route(path: "/register", name: 'register', methods: ['POST'])]
    public function register(RequestStack $requestStack): JsonResponse {
        $req = new RegRequest($requestStack);
        $errors = $this->validator->validate($req);
        if (count($errors) > 0) return new JsonResponse(['error' => (string)$errors], 400);
        $res = $this->authService->handleRegister($req->getEmail(), $req->getPassword(), $req->getFullName());
        return new JsonResponse($res);
    }
}