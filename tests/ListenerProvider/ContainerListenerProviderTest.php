<?php

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\EventDispatcher;
use Circli\EventDispatcher\ListenerProvider\ContainerListenerProvider;
use Fig\EventDispatcher\AggregateProvider;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Tests\Stubs\TestEvent;
use Tests\Stubs\TestServiceListener;

class ContainerListenerProviderTest extends TestCase
{
    private function getContainer(): ContainerInterface
    {
        return new class implements ContainerInterface {
            public function get(string $id)
            {
                if ($id === TestServiceListener::class) {
                    return new TestServiceListener();
                }
                return new class extends \RuntimeException implements NotFoundExceptionInterface {};
            }

            public function has(string $id): bool
            {
                return true;
            }
        };
    }

    public function testLazyLoad()
    {
        $listener = new ContainerListenerProvider($this->getContainer());
        $listener->addService(TestEvent::class, TestServiceListener::class);

        $dispatcher = new EventDispatcher($listener);

        $event = $dispatcher->dispatch(new TestEvent());

        $this->assertTrue($event->haveRun());
    }

    public function testAddNewEventType(): void
    {
        $listener = new ContainerListenerProvider($this->getContainer());
        $listener->addService(TestEvent::class, TestServiceListener::class);

        $this->assertCount(1, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testAddDuplicateCallback(): void
    {
        $listener = new ContainerListenerProvider($this->getContainer());
        $listener->addService(TestEvent::class, TestServiceListener::class);
        $listener->addService(TestEvent::class, TestServiceListener::class);

        $this->assertCount(2, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testNoListenersFound(): void
    {
        $listener = new ContainerListenerProvider($this->getContainer());
        $listener->addService('Dummy', Test::class);
        $this->assertCount(0, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testWithAggregateProvider(): void
    {
        $aggregateProvider = new AggregateProvider();
        $listener = new ContainerListenerProvider($this->getContainer());
        $aggregateProvider->addProvider($listener);

        $listener->addService(TestEvent::class, TestServiceListener::class);
        $count = 0;
        foreach ($aggregateProvider->getListenersForEvent(new TestEvent()) as $l) {
            $count++;
        }

        $this->assertSame(1, $count);
    }
}
