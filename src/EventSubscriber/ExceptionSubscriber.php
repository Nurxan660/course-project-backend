<?php

namespace App\EventSubscriber;


use App\Utils\ExceptionUtils;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function __construct(private ExceptionUtils $exceptionUtils,
                                private iterable $handlers)
    {
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