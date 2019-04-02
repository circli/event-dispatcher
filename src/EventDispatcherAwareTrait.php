<?php

namespace Circli\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventManager)
    {
        $this->eventDispatcher = $eventManager;
        return $this;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        if ($this->eventDispatcher === null) {
            $this->eventDispatcher = new NullEventDispatcher();
        }
        return $this->eventDispatcher;
    }
}