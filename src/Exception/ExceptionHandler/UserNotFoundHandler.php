<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CategoryNotFoundException;
use App\Exception\UserNotFoundException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class UserNotFoundHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UserNotFoundException) {
            return $exceptionUtils->createResponseWithTranslator('user_not_found');
        }
        return null;
    }
}