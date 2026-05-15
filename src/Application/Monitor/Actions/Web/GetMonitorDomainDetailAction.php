<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Admin\Models\Admin;
use Domain\Monitor\Dto\MonitorDomainDetailData;
use Domain\Monitor\Eloquent\{MonitorDomainReadEloquent, MonitorLogReadEloquent};

class GetMonitorDomainDetailAction
{
    public function __construct(
        private MonitorDomainReadEloquent $monitorDomainReadEloquent,
        private MonitorLogReadEloquent $monitorLogReadEloquent
    ) {}

    public function execute(Admin $admin, int $monitorDomainId): MonitorDomainDetailData
    {
        $monitorDomain = $this->monitorDomainReadEloquent->getByIdAndAdmin(admin: $admin, domainId: $monitorDomainId);
        $logs = $this->monitorLogReadEloquent->getDetail(domainId: $monitorDomain->id);

        return new MonitorDomainDetailData(domain: $monitorDomain, logs: $logs);
    }
}
