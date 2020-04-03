<?php declare(strict_types=1);

namespace Tests\Stubs;

class TestEvent
{
    private $count = 0;
    /** @var bool */
    private $done = false;

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
