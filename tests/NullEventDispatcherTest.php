<?php

namespace Tests;

use Circli\EventDispatcher\NullEventDispatcher;
use PHPUnit\Framework\TestCase;

class NullEventDispatcherTest extends TestCase
{
    public function testListen(): void
    {
        $eventDispatcher = new NullEventDispatcher();
        $this->assertSame($eventDispatcher, $eventDispatcher->listen('test', function () {}));
    }

    public function testTrigger(): void
    {
        $eventDispatcher = new NullEventDispatcher();
        $this->assertSame($eventDispatcher, $eventDispatcher->trigger('test'));
    }
}
