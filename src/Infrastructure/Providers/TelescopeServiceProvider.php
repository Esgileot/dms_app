<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\{IncomingEntry, Telescope, TelescopeApplicationServiceProvider};

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment(['local', 'dev'])) {
                return true;
            }

            return $entry->isReportableException()
                || $entry->isFailedRequest()
                || $entry->isFailedJob()
                || $entry->isScheduledTask()
                || $entry->hasMonitoredTag();
        });
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment(['local', 'dev'])) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders(['cookie', 'x-csrf-token', 'x-xsrf-token']);
    }

    protected function gate(): void
    {
        Gate::define('viewTelescope', static fn () => true);
    }
}
