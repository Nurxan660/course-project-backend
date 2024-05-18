<?php

namespace App\Exception\ExceptionHandler;

use App\Utils\ExceptionUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

interface ExceptionHandlerInterface
{
    public function handle(ExceptionEvent $event, ExceptionUtils $exceptionUtils): ?JsonResponse;
}