<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    private EventDispatcherInterface $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventManager): static
    {
        $this->eventDispatcher = $eventManager;
        return $this;
    }

    public function getEventDispatcher(): EventDispatcherInterface
    {
        if (!isset($this->eventDispatcher)) {
            $this->eventDispatcher = new NullEventDispatcher();
        }
        return $this->eventDispatcher;
    }
}
