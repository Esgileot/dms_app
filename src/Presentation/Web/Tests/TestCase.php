<?php

declare(strict_types=1);

namespace Presentation\Web\Tests;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\{RefreshDatabase, TestCase as BaseTestCase};
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Router;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Infrastructure\Middleware\TrimSpacesMiddleware;
use Presentation\Web\App\Http\Exceptions\Handler;

abstract class TestCase extends BaseTestCase
{
    use ErrorMessageHelper;
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureMiddlewares();

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        // Force WebServiceProvider configuration for testing
        $this->forceWebConfiguration();
        $this->registerWebRoutes();
    }

    private function configureMiddlewares(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];
        $router->middlewareGroup('web', [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            ValidateCsrfToken::class,
            SubstituteBindings::class,
            TrimSpacesMiddleware::class,
        ]);
    }

    private function forceWebConfiguration(): void
    {
        // Force web domain configuration
        config([
            'app.web_domain' => 'localhost'
        ]);

        // Register views for testing
        $this->app['view']->addNamespace('web', __DIR__ . '/../resources/views');

        // Force middleware configuration (already done in configureMiddlewares but ensuring it's set)
        $this->configureMiddlewares();
    }

    private function registerWebRoutes(): void
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->as('web.')
            ->middleware('web')
            ->namespace('Presentation\Web\App\Http\Controllers')
            ->group(__DIR__ . '/../routes/web.php');
    }
}
