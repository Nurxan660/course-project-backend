<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Exception\UserBlockedException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class CheckUserBlockedStatus implements EventSubscriberInterface
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @throws UserBlockedException
     */
    public function onKernelRequest(): void
    {
        $user = $this->security->getUser();

        if ($user instanceof User && $user->isBlocked()) {
            throw new UserBlockedException();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', -10]
        ];
    }
}