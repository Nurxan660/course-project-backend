<?php

namespace App\Exception\ExceptionHandler;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Contracts\Translation\TranslatorInterface;

interface ExceptionHandlerInterface
{
    public function handle(ExceptionEvent $event, TranslatorInterface $translator): ?JsonResponse;
}