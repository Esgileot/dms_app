<?php

declare(strict_types=1);

namespace Application\Monitor\Actions\Web;

use Domain\Monitor\Dto\MonitorDomainData;
use Domain\Monitor\Models\MonitorDomain;
use Domain\Admin\Models\Admin;
use Domain\Monitor\Eloquent\{
    MonitorDomainReadEloquent,
    MonitorDomainWriteEloquent,
    ScheduleSettingWriteEloquent
};
use Domain\Monitor\Services\{MonitorDomainService, ScheduleSettingService};
use Illuminate\Support\Facades\DB;
use Domain\Monitor\Exceptions\MonitorDomainAlreadyExistsException;
use Infrastructure\Exceptions\EntityUpdateException;
use Throwable;

class UpdateMonitorDomainAction
{
    public function __construct(
        private MonitorDomainService $monitorDomainService,
        private ScheduleSettingService $scheduleSettingService,
        private MonitorDomainReadEloquent $monitorDomainReadEloquent,
        private MonitorDomainWriteEloquent $monitorDomainWriteEloquent,
        private ScheduleSettingWriteEloquent $scheduleSettingWriteEloquent
    ) {}

    /**
     * @throws MonitorDomainAlreadyExistsException
     */
    public function execute(Admin $admin, MonitorDomainData $data, int $monitorDomainId): MonitorDomain
    {
        $this->monitorDomainService->checkOnExists(admin: $admin, domain: $data->domain, exceptId: $monitorDomainId);

        $monitorDomain = $this->monitorDomainReadEloquent->getByIdAndAdmin(admin: $admin, domainId: $monitorDomainId);

        $monitorDomain = $this->monitorDomainService->updateMonitorDomainModel(monitorDomain: $monitorDomain, data: $data);
        $scheduleSetting = $this->scheduleSettingService->updateScheduleSettingModel(scheduleSetting: $monitorDomain->scheduleSetting, data: $data);

        try {
            return DB::transaction(function () use ($monitorDomain, $scheduleSetting) {
                $monitorDomain = $this->monitorDomainWriteEloquent->save(monitorDomain: $monitorDomain);
                $scheduleSetting = $this->scheduleSettingWriteEloquent->save(scheduleSetting: $scheduleSetting);

                return $monitorDomain->setRelation('scheduleSetting', $scheduleSetting);
            }, 3);
        } catch (Throwable $e) {
            throw new EntityUpdateException('Failed to update monitor domain', previous: $e);
        }
    }
}
