<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Admin\Models\Admin;
use Domain\Monitor\Eloquent\{MonitorDomainReadEloquent, MonitorDomainWriteEloquent};
use Domain\Monitor\Exceptions\MonitorDomainFindException;

class DeleteMonitorDomainAction
{
    public function __construct(
        private MonitorDomainReadEloquent $monitorDomainReadEloquent,
        private MonitorDomainWriteEloquent $monitorDomainWriteEloquent,
    ) {}

    /**
     * @throws MonitorDomainFindException
     */
    public function execute(Admin $admin, int $monitorDomainId): void
    {
        $monitorDomain = $this->monitorDomainReadEloquent->getByIdAndAdmin(admin: $admin, domainId: $monitorDomainId);

        $this->monitorDomainWriteEloquent->delete(monitorDomain: $monitorDomain);
    }
}
