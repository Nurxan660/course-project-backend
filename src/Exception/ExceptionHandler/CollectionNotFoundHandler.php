<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CollectionNotFoundException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class CollectionNotFoundHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof CollectionNotFoundException) {
            return $exceptionUtils->createResponseWithTranslator('collection_not_found');
        }
        return null;
    }
}