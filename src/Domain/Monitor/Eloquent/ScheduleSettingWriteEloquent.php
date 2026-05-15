<?php

declare(strict_types=1);

namespace Domain\Monitor\Eloquent;

use Domain\Monitor\Models\ScheduleSetting;

class ScheduleSettingWriteEloquent
{
    public function save(ScheduleSetting $scheduleSetting): ScheduleSetting
    {
        $scheduleSetting->save();

        return $scheduleSetting;
    }
}
