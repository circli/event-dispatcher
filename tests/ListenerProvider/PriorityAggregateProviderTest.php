<?php declare(strict_types=1);

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Circli\EventDispatcher\ListenerProvider\PriorityAggregateProvider;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

final class PriorityAggregateProviderTest extends TestCase
{
    public function testPriorityAggregateProvider(): void
    {
        $aggregateProvider = new PriorityAggregateProvider();
        $listener1 = new DefaultProvider();
        $listener2 = new DefaultProvider();
        $aggregateProvider->addProvider($listener1);
        $aggregateProvider->addProviderWithPriority($listener2, 1500);

        $count = 0;
        $listener1->listen(TestEvent::class, function () use (&$count) {
            PriorityAggregateProviderTest::assertSame(1, $count);
            $count++;
        });
        $listener2->listen(TestEvent::class, function () use (&$count) {
            PriorityAggregateProviderTest::assertSame(0, $count);
            $count++;
        });

        foreach ($aggregateProvider->getListenersForEvent(new TestEvent()) as $l) {
            $l();
        }

        $this->assertSame(2, $count);
    }

    public function testPreventDuplicateProviders(): void
    {
        $aggregateProvider = new PriorityAggregateProvider();
        $listener1 = new DefaultProvider();
        $listener2 = $listener1;
        $aggregateProvider->addProvider($listener1);
        $aggregateProvider->addProviderWithPriority($listener2);

        $count = 0;
        $listener1->listen(TestEvent::class, function () use (&$count) {
            $count++;
        });

        foreach ($aggregateProvider->getListenersForEvent(new TestEvent()) as $l) {
            $l();
        }

        $this->assertSame(1, $count);
    }
}
