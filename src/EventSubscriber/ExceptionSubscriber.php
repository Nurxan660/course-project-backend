<?php

namespace App\EventSubscriber;

use App\Exception\CollectionNotFoundException;
use App\Exception\ExceptionHandler\CategoryNotFoundHandler;
use App\Exception\ExceptionHandler\CollectionNotFoundHandler;
use App\Exception\ExceptionHandler\UniqueConstraintViolationHandler;
use App\Exception\ExceptionHandler\ValidationExceptionHandler;
use App\Utils\ExceptionUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private array $handlers;

    public function __construct(private ExceptionUtils $exceptionUtils)
    {
        $this->handlers = [
            new UniqueConstraintViolationHandler(),
            new CategoryNotFoundHandler(),
            new ValidationExceptionHandler(),
            new CollectionNotFoundHandler()
        ];
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        foreach ($this->handlers as $handler) {
            $response = $handler->handle($event, $this->exceptionUtils);
            if ($response !== null) {
                $event->setResponse($response);
                break;
            }
        }
    }
}