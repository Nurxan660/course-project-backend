<?php

namespace App\Exception\ExceptionHandler;

use App\Exception\CreateTicketException;
use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class CreateTicketExceptionHandler implements ExceptionHandlerInterface
{

    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse
    {
        $exception = $event->getThrowable();
        if ($exception instanceof CreateTicketException) {
            return $exceptionUtils->createResponseWithTranslator('ticket_created_response');
        }
        return null;
    }
}