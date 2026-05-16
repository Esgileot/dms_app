<?php

declare(strict_types=1);

namespace Presentation\Background\App;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Presentation\Background\App\Commands\ScheduleDomainCheckCronCommand;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        ScheduleDomainCheckCronCommand::class
    ];


    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ScheduleDomainCheckCronCommand::class)
            ->everyFiveSeconds()
            ->withoutOverlapping();
    }

    protected function commands(): void
    {
        require __DIR__ . '/../routes/console.php';
    }
}
