<?php declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class MakeEntityCommandTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_an_entity_file()
    {
        $destination = app_path('Entities/Foo.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:entity Foo');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Entities;
        
        use Illuminate\Contracts\Support\{Arrayable, Jsonable};
        use JsonSerializable;
        use Ramsey\Uuid\{Uuid, UuidInterface};
        use Zain\LaravelDoctrine\Jetpack\Serializer\SerializesAttributes;
        
        class Foo implements Arrayable, Jsonable, JsonSerializable
        {
            use SerializesAttributes;
        
            protected UuidInterface $id;
        
            public function __construct()
            {
                $this->id = Uuid::uuid1();
            }
        
            public function getId(): string
            {
                return $this->id->toString();
            }
        }
        
        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @test
     * @environment-setup useModelNamespace
     */
    public function it_allows_configuring_namespace_of_entity_classes(): void
    {
        $destination = app_path('Models/Foo.php');

        // Make sure we're starting from a clean base.
        if (File::exists($destination)) {
            File::delete($destination);
        }

        $this->assertFalse(File::exists($destination), 'The destination file already exists.');

        Artisan::call('make:entity Foo');

        $this->assertTrue(File::exists($destination), 'The file was not generated where we expect.');

        $expected = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Models;
        
        use Illuminate\Contracts\Support\{Arrayable, Jsonable};
        use JsonSerializable;
        use Ramsey\Uuid\{Uuid, UuidInterface};
        use Zain\LaravelDoctrine\Jetpack\Serializer\SerializesAttributes;
        
        class Foo implements Arrayable, Jsonable, JsonSerializable
        {
            use SerializesAttributes;
        
            protected UuidInterface $id;
        
            public function __construct()
            {
                $this->id = Uuid::uuid1();
            }
        
            public function getId(): string
            {
                return $this->id->toString();
            }
        }
        
        CLASS;

        $this->assertEquals($expected, File::get($destination));
    }

    /**
     * @test
     */
    public function it_allows_creating_mapping_with_entity_class(): void
    {
        $entityFilePath = app_path('Entities/Foo.php');
        $mappingFilePath = app_path('Database/Doctrine/Mappings/FooMapping.php');

        // Make sure we're starting from a clean base.
        if (File::exists($entityFilePath)) {
            File::delete($entityFilePath);
        }
        // Make sure we're starting from a clean base.
        if (File::exists($mappingFilePath)) {
            File::delete($mappingFilePath);
        }

        $this->assertFalse(File::exists($entityFilePath), 'The destination entity file already exists.');
        $this->assertFalse(File::exists($mappingFilePath), 'The destination mapping file already exists.');

        Artisan::call('make:entity -m Foo');

        $this->assertTrue(File::exists($entityFilePath), 'The entity file was not generated where we expect.');
        $this->assertTrue(File::exists($mappingFilePath), 'The mapping file was not generated where we expect.');

        $entity = <<<'CLASS'
        <?php declare(strict_types=1);

        namespace App\Entities;
        
        use Illuminate\Contracts\Support\{Arrayable, Jsonable};
        use JsonSerializable;
        use Ramsey\Uuid\{Uuid, UuidInterface};
        use Zain\LaravelDoctrine\Jetpack\Serializer\SerializesAttributes;
        
        class Foo implements Arrayable, Jsonable, JsonSerializable
        {
            use SerializesAttributes;
        
            protected UuidInterface $id;
        
            public function __construct()
            {
                $this->id = Uuid::uuid1();
            }
        
            public function getId(): string
            {
                return $this->id->toString();
            }
        }
        
        CLASS;

        $this->assertEquals($entity, File::get($entityFilePath));

        $mapping = <<<'CLASS'
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
        $this->assertEquals($mapping, File::get($mappingFilePath));
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    public function useModelNamespace(Application $app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app->config;

        $config->set('jetpack.generators.namespaces.entities', 'Models');
    }
}
