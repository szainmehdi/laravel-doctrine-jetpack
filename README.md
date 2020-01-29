# Laravel Doctrine Jetpack

A set of productivity boosting helpers to make life with Laravel Doctrine simpler and faster.

[![Build Status](https://github.com/szainmehdi/laravel-doctrine-jetpack/workflows/Tests/badge.svg)](https://github.com/szainmehdi/laravel-doctrine-jetpack/actions?query=workflow%3ATests)
[![Latest Stable Version](https://poser.pugx.org/zain/laravel-doctrine-jetpack/v/stable)](https://packagist.org/packages/zain/laravel-doctrine-jetpack)
[![License](https://poser.pugx.org/zain/laravel-doctrine-jetpack/license)](https://packagist.org/packages/zain/laravel-doctrine-jetpack)
[![Total Downloads](https://poser.pugx.org/zain/laravel-doctrine-jetpack/downloads)](https://packagist.org/packages/zain/laravel-doctrine-jetpack)

## Installation

This package strictly requires:
- php >= 7.4

**Require** the package using Composer.

```bash
composer require szainmehdi/laravel-doctrine-jetpack
```

Laravel automatically discovers the package. No additional steps are necessary.

## Usage

### Generators

This package includes a few generators that allow you to speed up your workflow when it comes to writing entities and mapping classes using Laravel Doctrine.

#### Entity Generator

Generate a new Doctrine entity using the included stub by running the following `artisan` command:

```bash
php artisan make:doctrine:entity MyEntity
```

By default, this will create a new file in `app/Entities` called `MyEntity.php`, like so:

```php
<?php declare(strict_types=1);

namespace App\Entities;

use Illuminate\Contracts\Support\{Arrayable, Jsonable};
use JsonSerializable;
use Ramsey\Uuid\{Uuid, UuidInterface};
use Zain\LaravelDoctrine\Jetpack\Serializer\SerializesAttributes;

class MyEntity implements Arrayable, Jsonable, JsonSerializable
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
```

#### Fluent Mapping Generator

This package comes with a generator for [Laravel Doctrine's Fluent mapping driver](http://laraveldoctrine.org/docs/1.4/fluent), a very _Laravel-like_ way of writing your Doctrine mappings. 

The included command takes a _target entity_ as an argument and generates a mapping file.

```bash
php artisan make:doctrine:mapping MyEntity
```

By default, this will create a new file in `app/Database/Doctrine/Mappings/` called `MyEntityMapping.php`, like so:

```php
<?php declare(strict_types=1);

namespace App\Database\Doctrine\Mappings;

use App\Entities\MyEntity;
use LaravelDoctrine\Fluent\EntityMapping;
use LaravelDoctrine\Fluent\Fluent;

class MyEntityMapping extends EntityMapping
{
    public function mapFor()
    {
        return MyEntity::class;
    }

    public function map(Fluent $map)
    {
        $map->uuidPrimaryKey();
        // ...
        $map->timestamps();
    }
}
```

**Generate a Fluent Mapping class for a Value Object (Embeddable).**

```bash
php artisan make:doctrine:mapping MyValue --value
```

By default, this will create a new file in `app/Database/Doctrine/Mappings/Values/` called `MyValueMapping.php`. 

### Helpers

#### Entity Serialization

This package also includes a helper trait that allows you to make your entities (and potentially any other class) serializable to JSON or an array with just a single line.

```php
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Zain\LaravelDoctrine\Jetpack\Serializer\SerializesAttributes;

class MyEntity implements Arrayable, Jsonable, JsonSerializable
{
    // Add this trait to your entities
    use SerializesAttributes;
}
```

> **Note**: for the best experience with Laravel, I recommend having your Entity classes implement the three standard interfaces in the example above. This is not necessary but it will allow you to simply `return` your model from a controller, or inspect it in `tinker`. (I am not _recommending_ doing the former, but it is definitely useful for debugging in a pinch.)

## Development

All contributions are welcome. Found a bug? Open an issue in Github, or even better, submit a Pull Request.

### Running Tests
Check out the project locally, and run:

```bash
composer install

composer test

# or, using docker
docker-compose run --rm php composer test
```
