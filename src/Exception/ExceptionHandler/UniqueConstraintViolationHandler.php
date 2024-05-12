<?php

namespace App\Exception\ExceptionHandler;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

class UniqueConstraintViolationHandler implements ExceptionHandlerInterface
{
    public function handle(ExceptionEvent $event, TranslatorInterface $translator): ?JsonResponse
    {
        $exception = $event->getThrowable();
        $message = $translator->trans('email_already_exist', [], 'api_errors');
        if ($exception instanceof UniqueConstraintViolationException)
            return new JsonResponse(['error' => $message], 400);
        return null;
    }
}