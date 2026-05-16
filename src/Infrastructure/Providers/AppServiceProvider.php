<?php

declare(strict_types=1);

namespace Infrastructure\Providers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->useAppPath(__DIR__ . '/../');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    public function boot(): void
    {
        $this->prepareMorphMap(config('app.morph_map_classes'));
    }

    /**
     * @param array<class-string> $classes
     */
    private function prepareMorphMap(array $classes): void
    {
        Relation::enforceMorphMap(
            Arr::mapWithKeys($classes, static fn(string $class) => [
                Str::snake(class_basename($class)) => $class,
            ])
        );
    }
}
