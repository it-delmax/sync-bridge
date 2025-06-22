<?php

namespace App\Traits;

trait MeasuresElapsedTime
{
    protected float $startTime;

    public function startTimer(): void
    {
        $this->startTime = microtime(true);
    }

    public function elapsedMilliseconds(): int
    {
        return (int) round((microtime(true) - $this->startTime) * 1000);
    }

    public function elapsedSeconds(): float
    {
        return round((microtime(true) - $this->startTime), 3);
    }
}
