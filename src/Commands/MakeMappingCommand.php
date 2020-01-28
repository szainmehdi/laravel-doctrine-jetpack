<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeMappingCommand extends GeneratorCommand
{
    private const VALUE_MAPPER = 'EmbeddableMapping';
    private const ENTITY_MAPPER = 'EntityMapping';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:doctrine:mapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Doctrine Mapping';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mapping';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $namespace = $rootNamespace . '\\' . config('jetpack.mappings_namespace');

        if ($this->option('value')) {
            $namespace .= '\\' . 'Values';
        }

        return $namespace;
    }

    protected function getNameInput()
    {
        return parent::getNameInput() . 'Mapping';
    }

    protected function getOriginalNameInput(): string
    {
        return parent::getNameInput();
    }

    protected function buildClass($name)
    {
        $stub = parent::buildClass($name);
        $stub = $this->replaceMapper($stub);
        $stub = $this->replaceTargetClass($stub);
        $stub = $this->replacePlaceholderMapping($stub);

        return $stub;
    }

    protected function replaceMapper(string $stub): string
    {
        $mapper = $this->option('value') ? self::VALUE_MAPPER : self::ENTITY_MAPPER;
        return str_replace('SomeMapper', $mapper, $stub);
    }

    protected function replaceTargetClass(string $stub): string
    {
        $fqn = $this->getTargetClass();
        [$namespace, $class] = str_split($fqn, strrpos($fqn, '\\'));
        $class = trim($class, '\\');

        return str_replace(
            ['TargetNs', 'TargetCls'],
            [$namespace, $class],
            $stub
        );
    }

    protected function replacePlaceholderMapping(string $stub): string
    {
        return str_replace('PlaceholderMapping', $this->getPlaceholderMapping(), $stub);
    }

    private function getTargetClass(): string
    {
        // Make sure the entity name is in UpperCamelCase
        $name = Str::studly($this->getOriginalNameInput());

        // Replace directory separator with namespace separator
        $entity = str_replace(DIRECTORY_SEPARATOR, '\\', $name);

        $subNamespace = ($this->option('value')
            ? config('jetpack.values_namespace')
            : config('jetpack.entities_namespace'));

        $namespace = $this->rootNamespace() . $subNamespace . '\\';

        if (Str::startsWith($entity, $this->rootNamespace())) {
            $namespace = null;
        }

        return $namespace . $entity;
    }

    private function getPlaceholderMapping(): string
    {
        if ($this->option('value')) {
            return '// ...';
        }

        return <<<'TXT'
        $map->uuidPk();
                // ...
                $map->timestamps();
        TXT;
    }

    /**
     * @inheritDoc
     */
    protected function getStub()
    {
        return config('jetpack.stubs_dir') . 'mapping.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Force create mapping even if they already exists.'],
            ['value', null, InputOption::VALUE_NONE, 'Indicate that the object being mapped in a Value object'],
        ];
    }
}
