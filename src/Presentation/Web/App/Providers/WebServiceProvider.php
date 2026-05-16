<?php

declare(strict_types=1);

namespace Presentation\Web\App\Providers;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\{SubstituteBindings, ThrottleRequests, ValidateSignature};
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Infrastructure\Middleware\TrimSpacesMiddleware;
use Presentation\Web\App\Http\Exceptions\Handler;
use Presentation\Web\App\Http\Middleware\{EnsureEmailIsVerified, HandleInertiaRequests, RedirectIfAuthenticated, Authenticate, TrustProxies};

class WebServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->isHttpRequestEligible()) {
            $this->configureMiddlewares();
            $this->configureViews();
        }
    }

    public function register(): void
    {
        $this->app->register(WebRouteServiceProvider::class);

        if ($this->isHttpRequestEligible()) {
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }

    private function isHttpRequestEligible(): bool
    {
        /** @var Request $request */
        $request = request();
        $domain = config('app.web_domain');

        return $request->getHost() === $domain;
    }

    private function configureMiddlewares(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->middlewareGroup('web', [
            TrustProxies::class,
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            PreventRequestForgery::class,
            SubstituteBindings::class,
            HandleInertiaRequests::class,
            TrimSpacesMiddleware::class,
            ThrottleRequests::class . ':web',
        ]);

        $router->aliasMiddleware('signed', ValidateSignature::class);
        $router->aliasMiddleware('throttle', ThrottleRequests::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('verified', EnsureEmailIsVerified::class);
    }

    private function configureViews(): void
    {
        $this->loadViewsFrom(base_path('resources/views'), 'web');
    }
}
