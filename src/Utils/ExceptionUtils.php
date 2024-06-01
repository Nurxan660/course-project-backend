<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionUtils
{
    public function __construct(private TranslatorInterface $translator)
    {
    }

    public function createResponseWithTranslator(string $translationKey, int $statusCode = 400): JsonResponse
    {
        $message = $this->getExceptionTranslation($translationKey);
        return new JsonResponse(['message' => $message], $statusCode);
    }

    public function createResponse(string $message, int $statusCode): JsonResponse
    {
        return new JsonResponse(['message' => $message], $statusCode);
    }

    public function getExceptionTranslation(string $translationKey): string
    {
        return $this->translator->trans($translationKey, [], 'api_errors');
    }
}