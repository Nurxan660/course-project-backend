<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\Service\ErrorResponseService;
use App\Utils\ExceptionUtils;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class UniqueConstraintViolationHandler implements ExceptionHandlerInterface
{
    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UniqueConstraintViolationException) {
            return $exceptionUtils->createResponseWithTranslator('email_already_exist');
        }
        return null;
    }
}