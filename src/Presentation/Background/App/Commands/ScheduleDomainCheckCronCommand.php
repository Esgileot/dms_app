<?php

declare(strict_types=1);

namespace Presentation\Background\App\Commands;

use Carbon\Carbon;
use Domain\Monitor\Jobs\CheckDomainJob;
use Domain\Monitor\Models\MonitorDomain;
use Illuminate\Console\Command;

class ScheduleDomainCheckCronCommand extends Command
{
    protected $signature = 'monitor:check';

    public function handle(): int
    {
        MonitorDomain::query()
            ->where('next_check_at', '<=', Carbon::now())
            ->with('scheduleSetting')
            ->lazyById(100)
            ->each(function (MonitorDomain $monitorDomain): void {
                dispatch(new CheckDomainJob(domainId: $monitorDomain->id));

                $monitorDomain->next_check_at = Carbon::now()->addMinutes($monitorDomain->scheduleSetting->interval);
                $monitorDomain->save();
            });

        return self::SUCCESS;
    }
}
