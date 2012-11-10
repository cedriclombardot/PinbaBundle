<?php

namespace Cedriclombardot\PinbaBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Cedriclombardot\PinbaBundle\Pinba\Context;
use Cedriclombardot\PinbaBundle\Pinba\TimerManager;

class RequestListener
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;

        // Force Init TimerManager
        TimerManager::createInstance($context);
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->context->startTimerForRequest($event->getRequest());
    }
}
