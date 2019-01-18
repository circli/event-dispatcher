<?php

namespace Circli\EventManager;

trait EventDispatcherAwareTrait
{
    /** @var EventDispatcherInterface */
    private $eventManager;

    public function setEventManager(EventDispatcherInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    public function getEventManager(): EventDispatcherInterface
    {
        if ($this->eventManager === null) {
            $this->eventManager = new NullEventDispatcher();
        }
        return $this->eventManager;
    }
}