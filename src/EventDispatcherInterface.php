<?php

namespace Circli\EventDispatcher;

interface EventDispatcherInterface
{
    /**
     * @param string   $eventType
     * @param callable $callback
     *
     * @return static
     */
    public function listen(string $eventType, callable $callback);

    /**
     * @param mixed $event
     *
     * @return static
     */
    public function trigger($event);
}
