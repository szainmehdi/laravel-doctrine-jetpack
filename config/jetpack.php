<?php

use Zain\LaravelDoctrine\Jetpack\Providers\GeneratorServiceProvider;

return [
    'generators' => [
        /**
         * Load stubs files from this directory.
         * Make sure the path ends with a /
         * e.g. resource_path('jetpack/stubs/'),
         */
        'stubs_directory' => GeneratorServiceProvider::DEFAULT_STUBS_DIRECTORY,

        'namespaces' => [
            /**
             * Define the namespace (relative to your root namespace e.g. App\)
             * where your Entity classes are located.
             */
            'entities' => 'Entities',

            /**
             * Define the namespace (relative to your root namespace e.g. App\)
             * where your Value Object classes are located.
             *
             * These value objects can be mapped to the database as Embeddables.
             */
            'values' => 'Values',

            /**
             * Define the namespace (relative to your root namespace e.g. App\)
             * where your Fluent Mapping classes are located.
             */
            'mappings' => 'Database\Doctrine\Mappings',
        ],
    ],
];
