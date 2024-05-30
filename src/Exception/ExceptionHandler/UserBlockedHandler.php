<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CategoryNotFoundException;
use App\Exception\UserBlockedException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class UserBlockedHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof UserBlockedException) {
            return $exceptionUtils->createResponseWithTranslator('user_blocked');
        }
        return null;
    }
}