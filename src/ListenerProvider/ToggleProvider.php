<?php declare(strict_types=1);

namespace Circli\EventDispatcher\ListenerProvider;

use Psr\EventDispatcher\ListenerProviderInterface;

final class ToggleProvider implements ListenerProviderInterface
{
    private bool $active = true;

    public function __construct(
        private ListenerProviderInterface $provider,
    ) {}

    public function disable(): void
    {
        $this->active = false;
    }

    public function enable(): void
    {
        $this->active = true;
    }

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        if ($this->active === false) {
            return [];
        }
        yield from $this->provider->getListenersForEvent($event);
    }
}
