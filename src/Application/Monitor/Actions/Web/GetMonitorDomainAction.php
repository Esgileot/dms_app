<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Admin\Models\Admin;
use Domain\Monitor\Eloquent\MonitorDomainReadEloquent;
use Domain\Monitor\Exceptions\MonitorDomainFindException;
use Domain\Monitor\Models\MonitorDomain;

class GetMonitorDomainAction
{
    public function __construct(
        private MonitorDomainReadEloquent $monitorDomainReadEloquent
    ) {}

    /**
     * @throws MonitorDomainFindException
     */
    public function execute(int $domainId, Admin $admin): MonitorDomain
    {
        return $this->monitorDomainReadEloquent->getByIdAndAdmin(domainId: $domainId, admin: $admin);
    }
}
