<?php

declare(strict_types=1);

namespace Domain\Monitor\Eloquent;

use Domain\Monitor\Models\MonitorLog;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitorLogReadEloquent
{
    public function __construct(
        private MonitorLog $model
    ) {}

    public function getDetail(int $domainId): LengthAwarePaginator
    {
        return $this->model->query()
            ->where('monitor_domain_id', '=', $domainId)
            ->latest('checked_at')
            ->paginate(20);
    }
}
