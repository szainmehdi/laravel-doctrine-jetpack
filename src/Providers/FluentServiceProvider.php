<?php declare(strict_types=1);

namespace Zain\LaravelDoctrine\Jetpack\Providers;

use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\ServiceProvider;
use LaravelDoctrine\Fluent\Builders\Builder as Fluent;
use LaravelDoctrine\Fluent\Builders\Field;
use LaravelDoctrine\Fluent\Mapping;
use LaravelDoctrine\ORM\Extensions\MappingDriverChain;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Finder\Exception\DirectoryNotFoundException;
use Symfony\Component\Finder\Finder;

class FluentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if (config('doctrine.managers.default.meta') !== 'fluent') {
            return;
        }

        $this->registerUuidHelper();
        $this->registerMappings();
    }

    private function registerUuidHelper(): void
    {
        $type = config('jetpack.fluent.uuid_type');

        if ($type === null) {
            return;
        }

        if (!Type::hasType($type['name'])) {
            Type::addType($type['name'], $type['class']);
        }

        Fluent::macro('uuidPrimaryKey', fn(Fluent $builder): Field => $builder->field($type['name'], 'id')->primary());

        Fluent::macro('uuid', fn(Fluent $builder, string $name): Field => $builder->field($type['name'], $name));
    }

    private function registerMappings(): void
    {
        /** @var string|null $path */
        $path = config('jetpack.fluent.autoload_mappings_from');
        if ($path === null) {
            return;
        }

        $mappings = $this->getMappingClasses($path);

        try {
            $fluent = $this->app->make(EntityManager::class)->getConfiguration()->getMetadataDriverImpl();
        } catch (BindingResolutionException $e) {
            logger()->error('zain/laravel-doctrine-jetpack: Unable to resolve Doctrine entity manager. Check your Laravel Doctrine configuration.');
            return;
        }

        if (!($fluent instanceof MappingDriverChain)) {
            throw new LogicException("Expected MappingDriverChain, found " . get_class($fluent));
        }

        $fluent->addMappings($mappings);
    }

    private function getMappingClasses(string $path): array
    {
        $finder = new Finder();
        try {
            $finder->files()->in($path);
        } catch (DirectoryNotFoundException $e) {
            logger()->error("zain/laravel-doctrine-jetpack: Error auto-loading mappings. " . $e->getMessage());
            return [];
        }

        $namespace = $this->app->getNamespace() . trim(str_replace([app_path(), DIRECTORY_SEPARATOR], ['', '\\'], $path), '\\');
        $mappings = [];
        foreach ($finder as $file) {
            /** @var \Symfony\Component\Finder\SplFileInfo $file * */
            $replacements = [DIRECTORY_SEPARATOR => '\\', '.php' => ''];
            $className = str_replace(
                array_keys($replacements),
                array_values($replacements),
                $file->getRelativePathname()
            );

            try {
                $class = new ReflectionClass($namespace . '\\' . $className);
            } catch (ReflectionException $e) {
                logger()->error("zain/laravel-doctrine-jetpack: " . $e->getMessage());
                continue;
            }

            if ($class->implementsInterface(Mapping::class)) {
                $mappings[] = $className;
            }
        }

        return $mappings;
    }
}
