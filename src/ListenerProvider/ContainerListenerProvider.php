<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

use Psr\Container\ContainerInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ContainerListenerProvider implements ListenerProviderInterface
{
    /** @var array<string, array<int, callable>> */
    private array $listeners = [];

    public function __construct(
        private ContainerInterface $container,
    ) {}

    /**
     * @param class-string $service
     */
    public function addService(string $eventType, string $service): void
    {
        if (!isset($this->listeners[$eventType])) {
            $this->listeners[$eventType] = [];
        }

        $this->listeners[$eventType][] = function (object $event) use ($service) {
            $instance = $this->container->get($service);
            $instance($event);
        };
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

            foreach ($listeners as $listener) {
                yield $listener;
            }
        }
    }
}
