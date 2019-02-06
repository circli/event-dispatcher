<?php

namespace Tests;

use Circli\EventDispatcher\EventDispatcher;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

class EventDispatcherTest extends TestCase
{
    public function testsListen(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->listen(TestEvent::class, function (TestEvent $event) {
            $this->assertSame(1, $event->getCount());
        });

        $dispatcher->trigger(new TestEvent());
    }

    public function testsGetEvents(): void
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->listen(TestEvent::class, function () {});
        $dispatcher->listen(TestEvent::class, function () {});

        $this->assertCount(2, $dispatcher->getEvents(TestEvent::class));

        $this->assertCount(0, $dispatcher->getEvents('NoneExistent'));
    }
}
