<?php declare(strict_types=1);

namespace Tests\Stubs;

class TestEvent
{
    private $count = 0;

    public function __construct()
    {
        $this->count++;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
