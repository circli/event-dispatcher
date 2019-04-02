<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

class PriorityProvider implements ListenerInterface
{
    private $listeners = [];
    private $priorities = [];

    public function listen(string $eventType, callable $listener, int $priority = 1000): void
    {
        $priority = "$priority.0";

        if (!isset($this->listeners[$priority][$eventType])) {
            $this->listeners[$priority][$eventType] = [];
        }

        if (\in_array($listener, $this->listeners[$priority][$eventType], true)) {
            return;
        }

        $this->listeners[$priority][$eventType][] = $listener;

        $this->priorities = array_keys($this->listeners);
        usort($this->priorities, function ($a, $b) {
            return $b <=> $a;
        });
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->priorities as $priority) {
            foreach ($this->listeners[$priority] as $eventType => $listeners) {
                if ($event instanceof $eventType) {
                    yield from $listeners;
                }
            }
        }
    }
}
