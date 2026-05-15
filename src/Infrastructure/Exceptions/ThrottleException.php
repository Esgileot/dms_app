<?php

declare(strict_types=1);

namespace Infrastructure\Exceptions;

use Carbon\CarbonInterface;

class ThrottleException extends LogicException
{
    private string $time;

    public function __construct(int $time)
    {
        parent::__construct();

        $this->time = now()->diffForHumans(now()->addSeconds($time), CarbonInterface::DIFF_ABSOLUTE);
    }

    public function getTime(): string
    {
        return $this->time;
    }
}
