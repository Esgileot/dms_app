<?php

declare(strict_types=1);

namespace Domain\Monitor\Services;

use Carbon\Carbon;
use Domain\Admin\Models\Admin;
use Domain\Monitor\Dto\MonitorDomainData;
use Domain\Monitor\Eloquent\MonitorDomainReadEloquent;
use Domain\Monitor\Models\MonitorDomain;
use Domain\Monitor\Exceptions\MonitorDomainAlreadyExistsException;

class MonitorDomainService
{
    public function __construct(
        private MonitorDomainReadEloquent $monitorDomainReadEloquent
    ) {}

    /**
     * @throws MonitorDomainAlreadyExistsException
     */
    public function checkOnExists(Admin $admin, string $domain, ?int $exceptId = null): void
    {
        if ($this->monitorDomainReadEloquent->isExistsByDomain(admin: $admin, domain: $domain, exceptId: $exceptId)) {
            throw new MonitorDomainAlreadyExistsException();
        }
    }

    public function initMonitorDomainModel(Admin $admin, MonitorDomainData $data): MonitorDomain
    {
        $monitorDomain = new MonitorDomain();

        $monitorDomain->admin_id = $admin->id;
        $monitorDomain->domain = $data->domain;
        $monitorDomain->next_check_at = $this->calculateNextCheck(interval: $data->interval);

        return $monitorDomain;
    }

    public function updateMonitorDomainModel(MonitorDomain $monitorDomain, MonitorDomainData $data): MonitorDomain
    {
        $monitorDomain->domain = $data->domain;
        $monitorDomain->next_check_at = $this->calculateNextCheck(interval: $data->interval);

        return $monitorDomain;
    }

    private function calculateNextCheck(int $interval): Carbon
    {
        return Carbon::now()->addMinutes($interval);
    }
}
