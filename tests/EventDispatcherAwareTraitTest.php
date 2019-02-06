<?php

namespace Tests;

use Circli\EventDispatcher\EventDispatcher;
use Circli\EventDispatcher\EventDispatcherAwareTrait;
use Circli\EventDispatcher\NullEventDispatcher;
use PHPUnit\Framework\TestCase;

class EventDispatcherAwareTraitTest extends TestCase
{
    public function testSetDispatcher()
    {
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);

        $this->assertSame($trait, $trait->setEventDispatcher(new NullEventDispatcher()));
    }

    public function testGetDefaultDispatcher()
    {
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);
        $this->assertInstanceOf(NullEventDispatcher::class, $trait->getEventDispatcher());
    }

    public function testGetSetDispatcher()
    {
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);

        $dispatcher = new EventDispatcher();

        $this->assertSame($trait, $trait->setEventDispatcher($dispatcher));
        $this->assertInstanceOf(EventDispatcher::class, $trait->getEventDispatcher());
        $this->assertSame($dispatcher, $trait->getEventDispatcher());
    }
}
