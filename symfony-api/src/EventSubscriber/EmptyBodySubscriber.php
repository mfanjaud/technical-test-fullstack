<?php

namespace App\EventSubscriber;

use App\Exception\EmptyBodyException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class EmptyBodySubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'handleEmptyBody',
                EventPriorities::PRE_VALIDATE,
            ],
        ];
    }

    public function handleEmptyBody(RequestEvent $event)
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        if (
            !in_array($method, [Request::METHOD_POST, Request::METHOD_PUT])
        ) {
            return;
        }

        $data = $event->getRequest()->getContent();

        if (!$data) {
            throw new EmptyBodyException();
        }
    }
}
