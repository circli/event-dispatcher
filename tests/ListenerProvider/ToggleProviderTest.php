<?php declare(strict_types=1);

namespace Tests\ListenerProvider;

use Circli\EventDispatcher\ListenerProvider\DefaultProvider;
use Circli\EventDispatcher\ListenerProvider\ToggleProvider;
use PHPStan\Testing\TestCase;
use Tests\Stubs\TestEvent;

final class ToggleProviderTest extends TestCase
{
    /** @var DefaultProvider */
    private $defaultProvider;

    protected function setUp(): void
    {
        $this->defaultProvider = new DefaultProvider();
        $this->defaultProvider->listen(TestEvent::class, function() {});
        $this->defaultProvider->listen(TestEvent::class, function() {});
    }

    public function testActiveToggle(): void
    {
        $toggleProvider = new ToggleProvider($this->defaultProvider);
        $this->assertCount(2, $toggleProvider->getListenersForEvent(new TestEvent()));
    }

    public function testDisabledToggle(): void
    {
        $toggleProvider = new ToggleProvider($this->defaultProvider);
        $toggleProvider->disable();

        $this->assertCount(0, $toggleProvider->getListenersForEvent(new TestEvent()));
    }

    public function testReEnabledToggle(): void
    {
        $toggleProvider = new ToggleProvider($this->defaultProvider);
        $toggleProvider->disable();
        $this->assertCount(0, $toggleProvider->getListenersForEvent(new TestEvent()));
        $toggleProvider->enable();
        $this->assertCount(2, $toggleProvider->getListenersForEvent(new TestEvent()));
    }
}
