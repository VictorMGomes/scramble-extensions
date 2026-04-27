# Scramble Extensions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/victormgomes/scramble-extensions.svg?style=flat-square)](https://packagist.org/packages/victormgomes/scramble-extensions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/victormgomes/scramble-extensions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/victormgomes/scramble-extensions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/victormgomes/scramble-extensions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/victormgomes/scramble-extensions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/victormgomes/scramble-extensions.svg?style=flat-square)](https://packagist.org/packages/victormgomes/scramble-extensions)

## Useful magic extensions for dedoc/scramble package

---

### Extensions provided

#### AddAuthorizationHeader

To automatically add an Authorization header for authenticated routes

#### AddTenantHeader

To automatically add a Tenant header for Tenant routes

#### SpatieData

Add support to generate schema of DTOs from Spatie Data package

### Installation

```bash
composer require victormgomes/scramble-extensions
```

### Usage

Register any extension provided in the scramble config file

```php
//config/scramble.php
...
'extensions' => [
        \Victormgomes\ScrambleExtensions\Extensions\AddAuthorizationHeader::class,
        \Victormgomes\ScrambleExtensions\Extensions\AddTenantHeader::class,
        \Victormgomes\ScrambleExtensions\Extensions\SpatieData::class,
    ]
...
```

The result of registering these extensions above will look like:

![Alt text](media/images/01.png)

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Credits

- [Victor M. Gomes](https://github.com/VictorMGomes)

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
