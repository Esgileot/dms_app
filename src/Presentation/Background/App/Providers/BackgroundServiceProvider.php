<?php

declare(strict_types=1);

namespace Presentation\Background\App\Providers;

use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Exceptions\Handler;
use Presentation\Background\App\Kernel;

class BackgroundServiceProvider extends ServiceProvider
{
    public function boot(): void {}

    public function register(): void
    {
        if ($this->isRequestEligibleToAccessLayer()) {
            $this->app->singleton(ConsoleKernel::class, Kernel::class);
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }

    public function isRequestEligibleToAccessLayer(): bool
    {
        return app()->runningInConsole();
    }
}
