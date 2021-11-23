<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

final class FilterableProvider implements ListenerInterface
{
    /** @var array<string, array<array-key, array{callable, callable}>> */
    private $listeners = [];

    public function listen(string $eventType, callable $listener, callable $filter = null): void
    {
        $filter = $filter ?? static function () {return true;};
        if (!isset($this->listeners[$eventType])) {
            $this->listeners[$eventType] = [];
        }
        $entry = [$listener, $filter];

        if (\in_array($entry, $this->listeners[$eventType], true)) {
            return;
        }

        $this->listeners[$eventType][] = $entry;
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners as $eventType => $listeners) {
            if (!$event instanceof $eventType) {
                continue;
            }
            foreach ($listeners as [$listener, $filter]) {
                if ($filter($event)) {
                    yield $listener;
                }
            }
        }
    }
}
