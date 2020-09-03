<?php

namespace Tests;

use Circli\EventDispatcher\EventDispatcher;
use Circli\EventDispatcher\EventDispatcherAwareTrait;
use Circli\EventDispatcher\NullEventDispatcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;

class EventDispatcherAwareTraitTest extends TestCase
{
    public function testSetDispatcher()
    {
        /** @var MockObject|EventDispatcherAwareTrait $trait */
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);
        $this->assertSame($trait, $trait->setEventDispatcher(new NullEventDispatcher()));
    }

    public function testGetDefaultDispatcher()
    {
        /** @var MockObject|EventDispatcherAwareTrait $trait */
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);
        $this->assertInstanceOf(NullEventDispatcher::class, $trait->getEventDispatcher());
    }

    public function testGetSetDispatcher()
    {
        /** @var MockObject|EventDispatcherAwareTrait $trait */
        $trait = $this->getMockForTrait(EventDispatcherAwareTrait::class);

        $dispatcher = new EventDispatcher($this->createMock(ListenerProviderInterface::class));

        $this->assertSame($trait, $trait->setEventDispatcher($dispatcher));
        $this->assertInstanceOf(EventDispatcher::class, $trait->getEventDispatcher());
        $this->assertSame($dispatcher, $trait->getEventDispatcher());
    }
}
