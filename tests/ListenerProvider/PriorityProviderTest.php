<?php declare(strict_types=1);

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Circli\EventDispatcher\ListenerProvider\PriorityProvider;
use Fig\EventDispatcher\AggregateProvider;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

class PriorityProviderTest extends TestCase
{
    public function testAddNewEventType(): void
    {
        $listener = new PriorityProvider();
        $lowPriorityCallback = function() {};
        $highPriorityCallback = function() {};
        $normalPriorityCallback = function() {};
        $listener->listen(TestEvent::class, $lowPriorityCallback, 100);
        $listener->listen(TestEvent::class, $highPriorityCallback, 1100);
        $listener->listen(TestEvent::class, $normalPriorityCallback);

        $this->assertCount(3, iterator_to_array($listener->getListenersForEvent(new TestEvent())));

        $listeners = [];
        foreach ($listener->getListenersForEvent(new TestEvent()) as $tmp) {
            $listeners[] = $tmp;
        }

        $this->assertSame($listeners[0], $highPriorityCallback);
        $this->assertSame($listeners[1], $normalPriorityCallback);
        $this->assertSame($listeners[2], $lowPriorityCallback);
    }

    public function testAddDuplicateCallback(): void
    {
        $listener = new PriorityProvider();
        $callback = function () {};
        $listener->listen(TestEvent::class, $callback);
        $listener->listen(TestEvent::class, $callback);

        $this->assertCount(1, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testAddDuplicateDifferentPrioritiesCallback(): void
    {
        $listener = new PriorityProvider();
        $callback = function () {};
        $listener->listen(TestEvent::class, $callback);
        $listener->listen(TestEvent::class, $callback, 1);
        $listener->listen(TestEvent::class, $callback);

        $this->assertCount(2, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testWithAggregateProvider(): void
    {
        $aggregateProvider = new AggregateProvider();
        $listener = new PriorityProvider();
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
