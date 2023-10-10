<?php

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Fig\EventDispatcher\AggregateProvider;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

class DefaultProviderTest extends TestCase
{
    public function testAddNewEventType(): void
    {
        $listener = new DefaultProvider();
        $listener->listen(TestEvent::class, function() {});
        $listener->listen(TestEvent::class, function() {});

        $this->assertCount(2, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testAddDuplicateCallback(): void
    {
        $listener = new DefaultProvider();
        $callback = function () {};
        $listener->listen(TestEvent::class, $callback);
        $listener->listen(TestEvent::class, $callback);

        $this->assertCount(1, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testNoListenersFound(): void
    {
        $listener = new DefaultProvider();
        $listener->listen('Dummy', function () {});
        $this->assertCount(0, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testWithAggregateProvider(): void
    {
        $aggregateProvider = new AggregateProvider();
        $listener = new DefaultProvider();
        $aggregateProvider->addProvider($listener);
        $count = 0;
        $listener->listen(TestEvent::class, function () use(&$count) {
            $count++;
        });

        /** @var callable $l */
        foreach ($aggregateProvider->getListenersForEvent(new TestEvent()) as $l) {
            $l();
        }

        $this->assertSame(1, $count);
    }
}
