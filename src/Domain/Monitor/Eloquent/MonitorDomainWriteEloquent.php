<?php

declare(strict_types=1);

namespace Domain\Monitor\Eloquent;

use Domain\Monitor\Models\MonitorDomain;

class MonitorDomainWriteEloquent
{
    public function save(MonitorDomain $monitorDomain): MonitorDomain
    {
        $monitorDomain->save();

        return $monitorDomain;
    }

    public function delete(MonitorDomain $monitorDomain): void
    {
        $monitorDomain->delete();
    }
}
