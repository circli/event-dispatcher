<?php declare(strict_types=1);

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\FilterableProvider;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;
use Tests\Stubs\TestFilterEvent;

final class FilterableProviderTest extends TestCase
{
    public function testFilter(): void
    {
        $listener = new FilterableProvider();
        $listener->listen(TestFilterEvent::class, function () {}, function (TestFilterEvent $e) {
            return $e->getFilterValue() === 2;
        });
        $listener->listen(TestFilterEvent::class, function () {}, function (TestFilterEvent $e) {
            return $e->getFilterValue() === 3;
        });

        $this->assertCount(1, iterator_to_array($listener->getListenersForEvent(new TestFilterEvent(2))));
    }

    public function testAllFilterReturnFalse(): void
    {
        $listener = new FilterableProvider();
        $listener->listen(TestFilterEvent::class, function () {}, function (TestFilterEvent $e) {
            return false;
        });
        $this->assertCount(0, iterator_to_array($listener->getListenersForEvent(new TestFilterEvent(2))));
    }

    public function testNoListenerFound(): void
    {
        $listener = new FilterableProvider();
        $listener->listen(TestEvent::class, function () {}, function (TestEvent $e) {
            return false;
        });
        $this->assertCount(0, iterator_to_array($listener->getListenersForEvent(new TestFilterEvent(2))));
    }

    public function testAddDuplicateCallback(): void
    {
        $listener = new FilterableProvider();
        $callback = function () {};
        $filter = function () {return true;};
        $listener->listen(TestEvent::class, $callback, $filter);
        $listener->listen(TestEvent::class, $callback, $filter);

        $this->assertCount(1, iterator_to_array($listener->getListenersForEvent(new TestEvent())));
    }

    public function testDefaultFilter(): void
    {
        $listener = new FilterableProvider();
        $listener->listen(TestFilterEvent::class, function () {}, function (TestFilterEvent $e) {
            return $e->getFilterValue() === 2;
        });
        $listener->listen(TestFilterEvent::class, function () {});

        $this->assertCount(2, iterator_to_array($listener->getListenersForEvent(new TestFilterEvent(2))));
    }
}
