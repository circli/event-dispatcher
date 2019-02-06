<?php

namespace Circli\EventDispatcher;

interface EventDispatcherAwareInterface
{
    /**
     * @param EventDispatcherInterface $eventManager
     *
     * @return static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventManager);

    public function getEventDispatcher(): EventDispatcherInterface;

}