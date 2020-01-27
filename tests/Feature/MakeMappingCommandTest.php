<?php declare(strict_types=1);

namespace Tests\Feature;

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
                $map->uuidPk();
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
}
