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

        Artisan::call('make:doctrine:entity Foo');

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

        Artisan::call('make:doctrine:entity Foo');

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
     * @param \Illuminate\Foundation\Application $app
     */
    public function useModelNamespace(Application $app): void
    {
        /** @var \Illuminate\Config\Repository $config */
        $config = $app->config;

        $config->set('jetpack.generators.namespaces.entities', 'Models');
    }
}
