<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

use Fig\EventDispatcher\AggregateProvider;
use Psr\EventDispatcher\ListenerProviderInterface;

final class PriorityAggregateProvider extends AggregateProvider
{
    /** @var string[] */
    private array $priorities = [];

    public function addProvider(ListenerProviderInterface $provider): AggregateProvider
    {
        return $this->addProviderWithPriority($provider, 1000);
    }

    public function addProviderWithPriority(ListenerProviderInterface $provider, int $priority = 1000): self
    {
        $priority = "$priority.0";

        if (!isset($this->providers[$priority])) {
            $this->providers[$priority] = [];
        }

        if (\in_array($provider, $this->providers[$priority], true)) {
            return $this;
        }

        $this->providers[$priority][] = $provider;

        $this->priorities = array_keys($this->providers);
        usort($this->priorities, static function ($a, $b) {
            return $b <=> $a;
        });
        return $this;
    }

    public function merge(PriorityAggregateProvider $provider): self
    {
        foreach ($provider->providers as $priority => $providers) {
            if (!isset($this->providers[$priority])) {
                $this->providers[$priority] = $providers;
            }
            else {
                foreach ($providers as $p) {
                    if (\in_array($p, $this->providers[$priority], true)) {
                        continue;
                    }
                    $this->providers[$priority][] = $p;
                }
            }
        }
        $this->priorities = array_keys($this->providers);
        usort($this->priorities, static function ($a, $b) {
            return $b <=> $a;
        });
        return $this;
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->priorities as $priority) {
            foreach ($this->providers[$priority] as $provider) {
                yield from $provider->getListenersForEvent($event);
            }
        }
    }
}
