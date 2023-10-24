<?php declare(strict_types=1);

namespace Tests\Stubs;

class TestEvent
{
    private int $count = 0;
    private bool $done = false;

    public function __construct()
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function markAsExecuted(): void
    {
        $this->done = true;
    }

    public function haveRun(): bool
    {
        return $this->done;
    }
}
