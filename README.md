# Scramble Extensions

[![Latest Version on Packagist](https://img.shields.io/packagist/v/victormgomes/scramble-extensions.svg?style=flat-square)](https://packagist.org/packages/victormgomes/scramble-extensions)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/victormgomes/scramble-extensions/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/victormgomes/scramble-extensions/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/victormgomes/scramble-extensions/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/victormgomes/scramble-extensions/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/victormgomes/scramble-extensions.svg?style=flat-square)](https://packagist.org/packages/victormgomes/scramble-extensions)
[![License](https://img.shields.io/packagist/l/victormgomes/scramble-extensions.svg?style=flat-square)](https://packagist.org/packages/victormgomes/scramble-extensions)

**Useful extensions for the dedoc/scramble package**

---

## Introduction

**Scramble Extensions** provides a collection of powerful tools to supercharge your `dedoc/scramble` documentation. It automates common API documentation tasks like adding security headers, handling multi-tenancy, and providing deep integration for Spatie's Laravel Data DTOs.

### Why use this package?

*   **Automation**: Automatically add Authorization and Tenant headers to your OpenAPI specification.
*   **Rich DTO Support**: Seamlessly generate schemas for complex DTOs built with `spatie/laravel-data`.
*   **Clean Documentation**: Keep your controller annotations minimal by letting these extensions handle the global requirements of your API.
*   **Better DX**: Provide a much more accurate and interactive "Try It" experience in your documentation UI.

---

## Support us

We invest a lot of resources into creating [best in class open source packages](https://github.com/victormgomes). You can support us by [sponsoring us on GitHub](https://github.com/sponsors/VictorMGomes).

---

## Installation

```bash
composer require victormgomes/scramble-extensions
```

---

## Usage

Register the desired extensions in your `config/scramble.php` file:

```php
//config/scramble.php
return [
    // ...
    'extensions' => [
        \Victormgomes\ScrambleExtensions\Extensions\AddAuthorizationHeader::class,
        \Victormgomes\ScrambleExtensions\Extensions\AddTenantHeader::class,
        \Victormgomes\ScrambleExtensions\Extensions\SpatieData::class,
    ],
];
```

### Included Extensions

*   **AddAuthorizationHeader**: Automatically adds the Bearer token field to authenticated routes.
*   **AddTenantHeader**: Injects the required Tenant-ID header for multi-tenant routes.
*   **SpatieData**: Generates accurate OpenAPI schemas from `spatie/laravel-data` objects.

---

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Victor M. Gomes](https://github.com/VictorMGomes)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
