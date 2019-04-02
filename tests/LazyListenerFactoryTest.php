<?php

namespace Tests;

use Circli\EventDispatcher\LazyListenerFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Tests\Stubs\TestEvent;

class LazyListenerFactoryTest extends TestCase
{
    public function testCreateLazyCallback(): void
    {
        $resolver = $this->createMock(ContainerInterface::class);
        $resolver->expects($this->once())->method('get')->willReturn(function ($number) {
            $this->assertSame(2, $number);
        });

        $lazyFactory = new LazyListenerFactory($resolver);

        $listener = $lazyFactory->lazy('test');

        $this->assertIsCallable($listener);
        $listener(2);
    }

    public function testInvalidCallback(): void
    {
        $resolver = $this->createMock(ContainerInterface::class);
        $resolver->expects($this->once())->method('get')->willReturn(null);

        $lazyFactory = new LazyListenerFactory($resolver);

        $listener = $lazyFactory->lazy('test');
        $this->assertIsCallable($listener);

        $this->expectException(\InvalidArgumentException::class);
        $listener(new TestEvent());
    }
}
