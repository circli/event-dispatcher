<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

interface ListenerInterface extends ListenerProviderInterface
{
    public function listen(string $eventType, callable $listener) : void;
}
