<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Providers;

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
            ], 'config');
        }
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getConfigFilePath(), 'jetpack');
    }

    private function getConfigFilePath(): string
    {
        return __DIR__ . '/../../config/jetpack.php';
    }
}
