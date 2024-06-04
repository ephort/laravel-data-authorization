# Add authorization to your [`spatie/laravel-data`](https://github.com/spatie/laravel-data/) objects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ephort/laravel-data-authorization.svg?style=flat-square)](https://packagist.org/packages/ephort/laravel-data-authorization)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/ephort/laravel-data-authorization/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/ephort/laravel-data-authorization/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/ephort/laravel-data-authorization/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/ephort/laravel-data-authorization/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/ephort/laravel-data-authorization.svg?style=flat-square)](https://packagist.org/packages/ephort/laravel-data-authorization)

This package adds authorization to your [`spatie/laravel-data`](https://github.com/spatie/laravel-data/) objects, which
is very useful if you want to expose data objects to the frontend (e.g. when using Inertia), but still need to check
if the user is allowed to perform certain actions.

## Installation

Install the package via composer:

```shell
composer require ephort/laravel-data-authorization
```

## Usage

This package is intended to be used with [Inertia](https://inertiajs.com/), but does not require it or depend on it.

To add the authorization checks to your data objects, extend the `DataWithAuthorization` class.  
All the methods of the base `Data` class are still available.

Next, implement the static `getAuthorizations` method, which should return an array containing the
names of the actions that need to be exposed and checked.

```php
use Ephort\LaravelDataAuthorization\DataWithAuthorization;

class UserData extends DataWithAuthorization
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }
    
    public static function getAuthorizations(): array
    {
        return [
            'view',
            'update',
            'delete',
        ];
    }
}
```

When the data object is transformed, a lazy `authorization` property is appended to the resulting array.

This property contains a key for each defined policy action and is evaluated by `Gate::allows`.

```json
{
    "id": 1,
    "name": "Taylor Otwell",
    "authorization": {
        "view": true,
        "update": false,
        "delete": false
    }
}
```

### Avoid processing authorizations

Because the `authorization` property is lazy, we can exclude it from the data object to avoid calling the gate on every
serialization.

```php
UserData::from($user)->exclude('authorization');
```

Or use the built-in helper method:

```php
UserData::from($user)->withoutAuthorization();
```

### Note when using custom `from` methods

When using
a [custom `from` method](https://spatie.be/docs/laravel-data/v4/as-a-data-transfer-object/creating-a-data-object#content-magical-creation),
the pipeline that resolves authorizations is not used.

This means you must call the static `resolveAuthorizationArray` method manually when instantiating your
data object:

```php
public static function fromModel(User $user): self
{
    return self::from([
        'id' => $user->id,
        'name' => $user->name,
        'authorization' => static::resolveAuthorizationArray($user),
    ]);
}
```

You can also wrap the `authorization` array in a Lazy property if needed:

```php
Lazy::create(fn () => static::resolveAuthorizationArray($user))->defaultIncluded();
```

## TypeScript support

Thanks to Spatie, it's very easy to generate TypeScript interfaces from data objects and enums.  
Install the [TypeScript Transformer package](https://spatie.be/docs/typescript-transformer) and publish its
configuration file:

```shell
composer require spatie/laravel-typescript-transformer
php artisan vendor:publish --tag=typescript-transformer-config
```

Open `config/typescript-transformer.php` and add the following collector and transformer:

`Ephort\LaravelDataAuthorization\Collectors\DataAuthorizationTypeScriptCollector::class` must be the first collector.

```diff
'collectors' => [
+   Ephort\LaravelDataAuthorization\Collectors\DataAuthorizationTypeScriptCollector::class,
    Spatie\TypeScriptTransformer\Collectors\DefaultCollector::class,
    Spatie\TypeScriptTransformer\Collectors\EnumCollector::class,
],
```

```diff
'transformers' => [
    Spatie\LaravelTypeScriptTransformer\Transformers\SpatieStateTransformer::class,
    Spatie\TypeScriptTransformer\Transformers\EnumTransformer::class,
    Spatie\TypeScriptTransformer\Transformers\SpatieEnumTransformer::class,
    Spatie\LaravelTypeScriptTransformer\Transformers\DtoTransformer::class,
+   Ephort\LaravelDataAuthorization\Transformers\DataAuthorizationTypeScriptTransformer::class,
],
```

The above configuration uses a collector provided by this package, which finds data objects that
extend `DataWithAuthorization` and generates typings with their authorizations. This is what powers typed authorization
support.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

The code is primarily copied from the awesome project [Hybridly](https://github.com/hybridly/hybridly) by [Enzo
Innocenzi](https://x.com/enzoinnocenzi), which is a great alternative to [Inertia](https://inertiajs.com/).

- [Peter Brinck](https://github.com/peterbrinck)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
