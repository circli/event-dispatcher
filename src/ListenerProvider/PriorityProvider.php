<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

class PriorityProvider implements ListenerInterface
{
    /** @var array<string, array<string, array<array-key, callable>>> */
    private $listeners = [];
    /** @var string[] */
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
        usort($this->priorities, static function ($a, $b) {
            return $b <=> $a;
        });
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->priorities as $priority) {
            foreach ($this->listeners[$priority] as $eventType => $listeners) {
                if ($event instanceof $eventType) {
                    foreach ($listeners as $listener) {
                        yield $listener;
                    }
                }
            }
        }
    }
}
