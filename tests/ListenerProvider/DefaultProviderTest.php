<?php

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

class DefaultProviderTest extends TestCase
{
    public function testAddNewEventType(): void
    {
        $listener = new DefaultProvider();
        $listener->listen(TestEvent::class, function() {});
        $listener->listen(TestEvent::class, function() {});

        $this->assertCount(2, $listener->getListenersForEvent(new TestEvent()));
    }

    public function testAddDuplicateCallback(): void
    {
        $listener = new DefaultProvider();
        $callback = function () {};
        $listener->listen(TestEvent::class, $callback);
        $listener->listen(TestEvent::class, $callback);

        $this->assertCount(1, $listener->getListenersForEvent(new TestEvent()));
    }

    public function testNoListenersFound(): void
    {
        $listener = new DefaultProvider();
        $listener->listen('Dummy', function () {});
        $this->assertCount(0, $listener->getListenersForEvent(new TestEvent()));
    }
}
