<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionUtils
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function createResponseWithTranslator(string $translationKey, array $translationParams = [], int $statusCode = 400): JsonResponse {
        $message = $this->translator->trans($translationKey, $translationParams, 'api_errors');
        return new JsonResponse(['error' => $message], $statusCode);
    }

    public function createResponse(string $message, int $statusCode): JsonResponse {
        return new JsonResponse(['error' => $message], $statusCode);
    }
}