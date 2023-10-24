<?php declare(strict_types=1);

namespace Tests\Stubs;

class TestFilterEvent
{
    private int $count = 0;
    private bool $done = false;
    private mixed $filterValue;

    public function __construct(mixed $filterValue)
    {
        $this->count++;
        $this->filterValue = $filterValue;
    }

    public function getFilterValue(): mixed
    {
        return $this->filterValue;
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
