<?php declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Zain\LaravelDoctrine\Jetpack\GeneratorServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            GeneratorServiceProvider::class,
        ];
    }
}
