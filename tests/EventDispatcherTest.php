<?php

namespace Tests;

use Circli\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Tests\Stubs\TestEvent;

class EventDispatcherTest extends TestCase
{
    public function testDispatchToAllRelevantListeners(): void
    {
        $spy = (object) ['caught' => 0];
        $listeners = [];
        for ($i = 0; $i < 5; $i += 1) {
            $listeners[] = function (object $event) use ($spy) {
                $spy->caught += 1;
            };
        }
        $event = new TestEvent();

        $listener = $this->createMock(ListenerProviderInterface::class);
        $listener
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $dispatcher = new EventDispatcher($listener);
        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(5, $spy->caught);
    }

    public function testStopsOnException(): void
    {
        $event = new TestEvent();
        $exception = new \RuntimeException();
        $listeners = [];
        $listeners[] = function (object $event) use ($exception) {
            throw $exception;
        };

        $listener = $this->createMock(ListenerProviderInterface::class);
        $listener
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $dispatcher = new EventDispatcher($listener);
        $this->expectExceptionObject($exception);
        $dispatcher->dispatch($event);
    }

    public function testReturnEarlyIfPropagationIsStoppedBeforeDispatch(): void
    {
        $event = $this->createMock(StoppableEventInterface::class);
        $event
            ->expects($this->once())
            ->method('isPropagationStopped')
            ->willReturn(true);

        $listener = $this->createMock(ListenerProviderInterface::class);
        $listener
            ->expects($this->never())
            ->method('getListenersForEvent');

        $dispatcher = new EventDispatcher($listener);
        $this->assertSame($event, $dispatcher->dispatch($event));
    }

    public function testStopIfAnyListenersStopsPropagation()
    {
        $spy = (object) ['caught' => 0];
        $event = new class ($spy) implements StoppableEventInterface {
            private $spy;
            public function __construct(object $spy)
            {
                $this->spy = $spy;
            }
            public function isPropagationStopped() : bool
            {
                return $this->spy->caught > 3;
            }
        };
        $listeners = [];
        for ($i = 0; $i < 5; $i += 1) {
            $listeners[] = function (object $event) use ($spy) {
                $spy->caught += 1;
            };
        }

        $listener = $this->createMock(ListenerProviderInterface::class);
        $listener
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $dispatcher = new EventDispatcher($listener);
        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(4, $spy->caught);
    }
}
