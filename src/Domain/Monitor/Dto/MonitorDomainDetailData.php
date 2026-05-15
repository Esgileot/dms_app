<?php

declare(strict_types=1);

namespace Domain\Monitor\Dto;

use Spatie\LaravelData\Data;
use Domain\Monitor\Models\MonitorDomain;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitorDomainDetailData extends Data
{
    public function __construct(
        public MonitorDomain $domain,
        public LengthAwarePaginator $logs
    ) {}
}
