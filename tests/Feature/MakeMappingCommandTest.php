<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MakeMappingCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_maps_entities(): void
    {
        $destination = app_path('Database/Doctrine/Mappings/FooMapping.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:doctrine:mapping Foo');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Database\Doctrine\Mappings;
        
        use App\Entities\Foo;
        use LaravelDoctrine\Fluent\EntityMapping;
        use LaravelDoctrine\Fluent\Fluent;
        
        class FooMapping extends EntityMapping
        {
            public function mapFor()
            {
                return Foo::class;
            }
        
            public function map(Fluent $map)
            {
                $map->uuidPrimaryKey();
                // ...
                $map->timestamps();
            }
        }

        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @test
     */
    public function it_maps_value_objects(): void
    {
        $destination = app_path('Database/Doctrine/Mappings/Values/FooMapping.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:doctrine:mapping Foo --value');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Database\Doctrine\Mappings\Values;
        
        use App\Values\Foo;
        use LaravelDoctrine\Fluent\EmbeddableMapping;
        use LaravelDoctrine\Fluent\Fluent;
        
        class FooMapping extends EmbeddableMapping
        {
            public function mapFor()
            {
                return Foo::class;
            }
        
            public function map(Fluent $map)
            {
                // ...
            }
        }

        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @test
     * @environment-setup useCustomMappingNamespace
     */
    public function it_allows_configuring_namespace_of_mapping_class(): void
    {
        $destination = app_path('Database/Definitions/Mappings/FooMapping.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:doctrine:mapping Foo');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Database\Definitions\Mappings;
        
        use App\Entities\Foo;
        use LaravelDoctrine\Fluent\EntityMapping;
        use LaravelDoctrine\Fluent\Fluent;
        
        class FooMapping extends EntityMapping
        {
            public function mapFor()
            {
                return Foo::class;
            }
        
            public function map(Fluent $map)
            {
                $map->uuidPrimaryKey();
                // ...
                $map->timestamps();
            }
        }

        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @test
     * @environment-setup useCustomMappingNamespace
     * @environment-setup useCustomEntityNamespace
     */
    public function it_handles_all_customized_namespaces(): void
    {
        $destination = app_path('Database/Definitions/Mappings/BarMapping.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:doctrine:mapping Bar');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Database\Definitions\Mappings;
        
        use App\Domain\Models\Entities\Bar;
        use LaravelDoctrine\Fluent\EntityMapping;
        use LaravelDoctrine\Fluent\Fluent;
        
        class BarMapping extends EntityMapping
        {
            public function mapFor()
            {
                return Bar::class;
            }
        
            public function map(Fluent $map)
            {
                $map->uuidPrimaryKey();
                // ...
                $map->timestamps();
            }
        }

        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function useCustomMappingNamespace(Application $app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app->config;

        $config->set('jetpack.generators.namespaces.mappings', 'Database\Definitions\Mappings');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function useCustomEntityNamespace(Application $app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app->config;

        $config->set('jetpack.generators.namespaces.entities', 'Domain\Models\Entities');
    }
}
