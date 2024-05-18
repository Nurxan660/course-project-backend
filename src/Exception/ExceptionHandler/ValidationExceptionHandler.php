<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\ValidationException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ValidationExceptionHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ValidationException) {
            return $exceptionUtils->createResponse($exception->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return null;
    }
}