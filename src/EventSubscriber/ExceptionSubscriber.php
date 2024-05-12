<?php

namespace App\EventSubscriber;

use App\Exception\ExceptionHandler\UniqueConstraintViolationHandler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private array $handlers;

    public function __construct(private TranslatorInterface $translator)
    {
        $this->handlers = [
            new UniqueConstraintViolationHandler(),
        ];
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::EXCEPTION => 'onKernelException'];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        foreach ($this->handlers as $handler) {
            $response = $handler->handle($event, $this->translator);
            if ($response !== null) {
                $event->setResponse($response);
                break;
            }
        }
    }
}