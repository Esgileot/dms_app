<?php

declare(strict_types=1);

namespace Presentation\Web\App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{RateLimiter, Route};

class WebRouteServiceProvider extends RouteServiceProvider
{
    protected $namespace = 'Presentation\Web\App\Http\Controllers';

    public function boot(): void
    {
        parent::boot();

        $this->configureRateLimiting();
    }

    public function map(): void
    {
        Route::domain(config('app.web_domain'))
            ->namespace($this->namespace)
            ->middleware('web')
            ->as('web.')
            ->group(__DIR__ . '/../../routes/web.php');
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('web', static function (Request $request) {
            if (config('app.web_rate_limiting_enable') === false) {
                return Limit::none();
            }

            return Limit::perMinute(60)->by(self::getUserIdentifier($request));
        });
    }

    private static function getUserIdentifier(Request $request): string
    {
        return (string) $request->user()?->id ?? $request->ip();
    }
}
