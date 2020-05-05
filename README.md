# Displays metrics for your laravel vapor projects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/fidum/laravel-dashboard-vapor-metrics-tile/run-tests?label=tests)](https://github.com/fidum/laravel-dashboard-vapor-metrics-tile/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)

This tile can be used on [the Laravel Dashboard]https://docs.spatie.be/laravel-dashboard).

## Installation

You can install the package via composer:

```bash
composer require fidum/laravel-dashboard-vapor-metrics-tile
```

## Usage

In your dashboard view you use the component.

```html
<x-dashboard>
    <livewire:vapor-environment-metrics tileName="My Production Project" position="a1:a2" />
</x-dashboard>
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
