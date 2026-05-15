<?php

declare(strict_types=1);

namespace Domain\Monitor\Dto;

use Spatie\LaravelData\Data;
use Domain\Monitor\Enums\MonitorMethodEnum;

class MonitorDomainData extends Data
{
    public function __construct(
        public string $domain,
        public int $interval,
        public int $timeout,
        public MonitorMethodEnum $method
    ) {}
}
