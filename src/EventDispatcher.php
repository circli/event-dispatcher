<?php

namespace Circli\EventDispatcher;

class EventDispatcher implements EventDispatcherInterface
{
    /** @var array<string, callable> */
    private $events = [];

    /**
     * @param string $eventType the class name of the event
     * @param callable callback
     * @return static
     */
    public function listen(string $eventType, callable $callback)
    {
        if (!isset($this->events[$eventType])) {
            $this->events[$eventType] = [];
        }
        $this->events[$eventType][] = $callback;
        return $this;
    }

    public function trigger($event)
    {
        foreach ($this->getEvents(\get_class($event)) as $callback) {
            $callback($event);
        }
        return $this;
    }

    public function getEvents(string $eventType): array
    {
        if (isset($this->events[$eventType])) {
            return $this->events[$eventType];
        }
        return [];
    }
}
