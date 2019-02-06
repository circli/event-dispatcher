<?php

namespace Tests;

use Circli\EventDispatcher\LazyListenerFactory;
use Circli\EventDispatcher\ListenerResolverInterface;
use PHPUnit\Framework\TestCase;

class LazyListenerFactoryTest extends TestCase
{
    public function testCreateLazyCallback(): void
    {
        $resolver = $this->createMock(ListenerResolverInterface::class);
        $resolver->expects($this->once())->method('resolve')->willReturn(function ($number) {
            $this->assertSame(2, $number);
        });

        $lazyFactory = new LazyListenerFactory($resolver);

        $listener = $lazyFactory->lazy('test');

        $this->assertIsCallable($listener);
        $listener(2);
    }
}
