<?php declare(strict_types=1);

namespace Tests\Feature;

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
        
        use Ramsey\Uuid\Uuid;
        use Ramsey\Uuid\UuidInterface;
        
        class Foo
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
}
