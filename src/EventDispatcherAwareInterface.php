<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

interface EventDispatcherAwareInterface
{
    /**
     * @param EventDispatcherInterface $eventManager
     *
     * @return static
     */
    public function setEventDispatcher(EventDispatcherInterface $eventManager): static;

    public function getEventDispatcher(): EventDispatcherInterface;
}
