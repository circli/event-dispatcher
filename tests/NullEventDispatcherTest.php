<?php declare(strict_types=1);

namespace Tests;

use Circli\EventDispatcher\NullEventDispatcher;
use PHPUnit\Framework\TestCase;
use Tests\Stubs\TestEvent;

class NullEventDispatcherTest extends TestCase
{
    public function testDispatch(): void
    {
        $eventDispatcher = new NullEventDispatcher();
        $event = new TestEvent();
        $this->assertSame($event, $eventDispatcher->dispatch($event));
    }
}
