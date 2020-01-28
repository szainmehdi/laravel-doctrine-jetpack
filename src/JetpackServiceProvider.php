<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack;

use Illuminate\Support\ServiceProvider;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeEntityCommand;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeMappingCommand;

class JetpackServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                $this->getConfigFilePath() => config_path('jetpack.php'),
            ]);
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFilePath(), 'jetpack');

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeEntityCommand::class,
                MakeMappingCommand::class,
            ]);
        }
    }

    public function getConfigFilePath(): string
    {
        return __DIR__ . '/../config/jetpack.php';
    }
}
