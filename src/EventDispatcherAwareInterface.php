<?php

namespace Circli\EventManager;

interface EventDispatcherAwareInterface
{
    /**
     * @param EventDispatcherInterface $eventManager
     *
     * @return static
     */
    public function setEventManager(EventDispatcherInterface $eventManager);

    public function getEventManager(): EventDispatcherInterface;

}