<?php

declare(strict_types=1);

namespace Domain\Monitor\Services;

use Domain\Monitor\Dto\MonitorDomainData;
use Domain\Monitor\Models\ScheduleSetting;

class ScheduleSettingService
{
    public function initScheduleSettingModel(MonitorDomainData $data): ScheduleSetting
    {
        $scheduleSetting = new ScheduleSetting();

        $scheduleSetting->interval = $data->interval;
        $scheduleSetting->timeout = $data->timeout;
        $scheduleSetting->method = $data->method;

        return $scheduleSetting;
    }

    public function updateScheduleSettingModel(ScheduleSetting $scheduleSetting, MonitorDomainData $data): ScheduleSetting
    {
        $scheduleSetting->interval = $data->interval;
        $scheduleSetting->timeout = $data->timeout;
        $scheduleSetting->method = $data->method;

        return $scheduleSetting;
    }
}
