<?php

namespace Circli\EventDispatcher;

trait EventDispatcherAwareTrait
{
    /** @var EventDispatcherInterface */
    private $eventManager;

    public function setEventDispatcher(EventDispatcherInterface $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        if ($this->eventManager === null) {
            $this->eventManager = new NullEventDispatcher();
        }
        return $this->eventManager;
    }
}