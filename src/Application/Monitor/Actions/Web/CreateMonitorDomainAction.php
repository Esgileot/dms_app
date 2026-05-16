<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Monitor\Dto\MonitorDomainData;
use Domain\Monitor\Models\MonitorDomain;
use Domain\Admin\Models\Admin;
use Domain\Monitor\Eloquent\{MonitorDomainWriteEloquent, ScheduleSettingWriteEloquent};
use Domain\Monitor\Services\{MonitorDomainService, ScheduleSettingService};
use Illuminate\Support\Facades\DB;
use Domain\Monitor\Exceptions\MonitorDomainAlreadyExistsException;
use Infrastructure\Exceptions\EntityCreateException;
use Throwable;

class CreateMonitorDomainAction
{
    public function __construct(
        private MonitorDomainService $monitorDomainService,
        private ScheduleSettingService $scheduleSettingService,
        private MonitorDomainWriteEloquent $monitorDomainWriteEloquent,
        private ScheduleSettingWriteEloquent $scheduleSettingWriteEloquent
    ) {}

    /**
     * @throws MonitorDomainAlreadyExistsException
     */
    public function execute(Admin $admin, MonitorDomainData $data): MonitorDomain
    {
        $this->monitorDomainService->checkOnExists(admin: $admin, domain: $data->domain);

        $monitorDomain = $this->monitorDomainService->initMonitorDomainModel(admin: $admin, data: $data);
        $scheduleSetting = $this->scheduleSettingService->initScheduleSettingModel(data: $data);

        try {
            return DB::transaction(function () use ($monitorDomain, $scheduleSetting) {
                $monitorDomain = $this->monitorDomainWriteEloquent->save(monitorDomain: $monitorDomain);

                $scheduleSetting->monitor_domain_id = $monitorDomain->id;
                $scheduleSetting = $this->scheduleSettingWriteEloquent->save(scheduleSetting: $scheduleSetting);

                return $monitorDomain->setRelation('scheduleSetting', $scheduleSetting);
            }, 3);
        } catch (Throwable $e) {
            throw new EntityCreateException('Failed to create monitor domain', previous: $e);
        }
    }
}
