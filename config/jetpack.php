<?php
use Zain\LaravelDoctrine\Jetpack\JetpackServiceProvider;

return [
    /**
     * Define the namespace (relative to your root namespace e.g. App\)
     * where your Entity classes are located.
     */
    'entities_namespace' => 'Entities',

    /**
     * Define the namespace (relative to your root namespace e.g. App\)
     * where your Value Object classes are located.
     *
     * These value objects can be mapped to the database as Embeddables.
     */
    'values_namespace' => 'Values',

    /**
     * Define the namespace (relative to your root namespace e.g. App\)
     * where your Fluent Mapping classes are located.
     */
    'mappings_namespace' => 'Database\Doctrine\Mappings',

    /**
     * Load stubs files from this directory.
     * Make sure the path ends with a /
     * e.g. resource_path('jetpack/stubs/'),
     */
     'stubs_dir' => JetpackServiceProvider::DEFAULT_STUBS_DIRECTORY,
];
