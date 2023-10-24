<?php declare(strict_types=1);

namespace Tests;

use Circli\EventDispatcher\EventDispatcher;
use Circli\EventDispatcher\SilentEventDispatcher;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Psr\Log\LoggerInterface;
use Tests\Stubs\TestEvent;

class SilentEventDispatcherTest extends TestCase
{
    public function testNormalDispatch(): void
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

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('warning');

        $dispatcher = new SilentEventDispatcher($listener, $logger);
        $this->assertSame($event, $dispatcher->dispatch($event));
        $this->assertSame(5, $spy->caught);
    }

    public function testDontStopOnException(): void
    {
        $spy = (object) ['caught' => 0];
        $event = new TestEvent();
        $exception = new \RuntimeException();
        $listeners = [];
        $listeners[] = function (object $event) use ($exception) {
            throw $exception;
        };
        $listeners[] = function (object $event) use ($spy) {
            $spy->caught += 1;
        };

        $listener = $this->createMock(ListenerProviderInterface::class);
        $listener
            ->expects($this->once())
            ->method('getListenersForEvent')
            ->with($event)
            ->willReturn($listeners);

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->once())->method('warning');

        $dispatcher = new SilentEventDispatcher($listener, $logger);
        $dispatcher->dispatch($event);

        $this->assertSame(1, $spy->caught);
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

        $logger = $this->createMock(LoggerInterface::class);
        $logger->expects($this->never())->method('warning');

        $dispatcher = new SilentEventDispatcher($listener, $logger);
        $this->assertSame($event, $dispatcher->dispatch($event));
    }
}
