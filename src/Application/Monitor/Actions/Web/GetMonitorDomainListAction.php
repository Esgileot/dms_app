<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Admin\Models\Admin;
use Domain\Monitor\Eloquent\MonitorDomainReadEloquent;
use Illuminate\Pagination\LengthAwarePaginator;

class GetMonitorDomainListAction
{
    public function __construct(
        private MonitorDomainReadEloquent $monitorDomainReadEloquent,
    ) {}

    public function execute(Admin $admin): LengthAwarePaginator
    {
        return $this->monitorDomainReadEloquent->getList(admin: $admin);
    }
}
