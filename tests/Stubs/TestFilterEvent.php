<?php declare(strict_types=1);

namespace Tests\Stubs;

class TestFilterEvent
{
    private $count = 0;
    /** @var bool */
    private $done = false;
    private $filterValue;

    public function __construct($filterValue)
    {
        $this->count++;
        $this->filterValue = $filterValue;
    }

    public function getFilterValue()
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
