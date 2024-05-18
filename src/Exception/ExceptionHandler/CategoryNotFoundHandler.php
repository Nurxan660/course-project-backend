<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CategoryNotFoundException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class CategoryNotFoundHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof CategoryNotFoundException) {
            return $exceptionUtils->createResponseWithTranslator('category_not_found');
        }
        return null;
    }
}