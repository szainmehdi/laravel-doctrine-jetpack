<?php declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Zain\LaravelDoctrine\Jetpack\Providers\GeneratorServiceProvider;
use Zain\LaravelDoctrine\Jetpack\Providers\JetpackServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            JetpackServiceProvider::class,
            GeneratorServiceProvider::class,
        ];
    }
}
