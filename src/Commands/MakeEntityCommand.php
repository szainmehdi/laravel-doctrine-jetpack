<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Commands;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeEntityCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:entity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Doctrine Entity class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Entity';

    /**
     * Execute the console command.
     *
     * @return bool|null
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        if ($this->option('map')) {
            $this->createMapping();
        }

        return true;
    }

    /**
     * Create a mapping for the entity.
     */
    protected function createMapping(): void
    {
        $this->call('make:mapping', [
            'name' => $this->getNameInput(),
            '--force' => $this->option('force')
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('jetpack.generators.stubs_directory', ) . 'entity.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . config('jetpack.generators.namespaces.entities');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['map', 'm', InputOption::VALUE_NONE, 'Also create a new mapping file for the entity'],
            ['force', 'f', InputOption::VALUE_NONE, 'Force create files even if they already exists.'],
        ];
    }
}
