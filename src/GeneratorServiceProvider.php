<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack;

use Illuminate\Support\ServiceProvider;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeEntityCommand;
use Zain\LaravelDoctrine\Jetpack\Commands\MakeMappingCommand;

class GeneratorServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->commands([
            MakeEntityCommand::class,
            MakeMappingCommand::class,
        ]);
    }
}
