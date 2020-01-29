<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Providers;

use Illuminate\Support\ServiceProvider;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeEntityCommand;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeMappingCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    public const DEFAULT_STUBS_DIRECTORY = __DIR__ . '/../../resources/stubs/';

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getResourceDirectory() => resource_path('jetpack'),
            ], 'jetpack-stubs');
        }
    }

    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeEntityCommand::class,
                MakeMappingCommand::class,
            ]);
        }
    }

    private function getResourceDirectory(): string
    {
        return __DIR__ . '/../../resources';
    }
}
