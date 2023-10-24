<?php declare(strict_types=1);

namespace Tests\Stubs;

final class TestServiceListener
{
    public function __invoke(TestEvent $event): void
    {
        $event->markAsExecuted();
    }
}
