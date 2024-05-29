<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CategoryNotFoundException;
use App\Exception\ItemNotFoundException;
use App\Exception\UserNotFoundException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ItemNotFoundHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof ItemNotFoundException) {
            return $exceptionUtils->createResponseWithTranslator('item_not_found');
        }
        return null;
    }
}