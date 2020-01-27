# Laravel Doctrine Jetpack

A set of productivity boosting helpers to make life with Laravel Doctrine simpler and faster.

## Installation

This package strictly requires:
- php >= 7.4

**Require** the package using Composer.

```bash
composer require szainmehdi/laravel-doctrine-jetpack
```

Laravel automatically discovers the package. No additional steps are necessary.

## Usage

**Generate an Entity class.**

```bash
php artisan make:doctrine:entity MyEntity
```

By default, this will create a new file in `app/Entities` called `MyEntity.php`. 

**Generate a Fluent Mapping class for a Doctrine Entity.**

```bash
php artisan make:doctrine:mapping MyEntity
```

By default, this will create a new file in `app/Database/Doctrine/Mappings/` called `MyEntityMapping.php`. 

**Generate a Fluent Mapping class for a Value Object (Embeddable).**

```bash
php artisan make:doctrine:mapping MyValue --value
```

By default, this will create a new file in `app/Database/Doctrine/Mappings/Values/` called `MyValueMapping.php`. 

## Running Tests
Check out the project locally, and run:

```bash
composer install

composer test

# or, using docker
docker-compose run --rm php composer test
```

## TODOs
- [ ] Make everything configurable easily.
- [ ] Eventually make alternative mapping stubs?
