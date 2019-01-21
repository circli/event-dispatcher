<?php

namespace Circli\EventDispatcher;

class NullEventDispatcher implements EventDispatcherInterface
{
    /**
     * @param string $eventType
     * @param callable $callback
     *
     * @return static
     */
    public function listen(string $eventType, callable $callback)
    {
        return $this;
    }

    /**
     * @param mixed $event
     *
     * @return static
     */
    public function trigger($event)
    {
        return $this;
    }
}