<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;

class NullEventDispatcher implements EventDispatcherInterface
{
    /**
     * @inheritDoc
     */
    public function dispatch(object $event)
    {
        return $event;
    }
}
