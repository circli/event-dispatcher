<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

class DefaultProvider implements ListenerInterface
{
    private $listeners = [];

    public function listen(string $eventType, callable $listener): void
    {
        if (!isset($this->listeners[$eventType])) {
            $this->listeners[$eventType] = [];
        }

        if (\in_array($listener, $this->listeners[$eventType], true)) {
            return;
        }

        $this->listeners[$eventType][] = $listener;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $eventType => $listeners) {
            if (!$event instanceof $eventType) {
                continue;
            }

            yield from $listeners;
        }
    }
}
