<?php declare(strict_types=1);

namespace Circli\EventDispatcher;

use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;

final class SilentEventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private ListenerProviderInterface $provider,
        private LoggerInterface $logger,
    ) {}

    public function dispatch(object $event)
    {
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->provider->getListenersForEvent($event) as $listener) {
            try {
                $listener($event);

                if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
                    break;
                }
            }
            catch (\Exception $e) {
                $this->logger->warning('Failed to execute event listener', [
                    'event' => get_class($event),
                    'message' => $e,
                    'exception' => $e,
                ]);
            }
        }

        return $event;
    }
}
