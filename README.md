# Displays metrics for your laravel vapor projects

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/fidum/laravel-dashboard-vapor-metrics-tile/run-tests?label=tests)](https://github.com/fidum/laravel-dashboard-vapor-metrics-tile/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)

![Preview](docs/preview.png)

This tile can be used on [the Laravel Dashboard](https://docs.spatie.be/laravel-dashboard).

## Installation

You can install the package via composer:

```bash
composer require fidum/laravel-dashboard-vapor-metrics-tile
```

## Usage

In your dashboard view you can use one or all or multiple of each of these components. The `tileName` and `position` attributes are **required**:

```html
<x-dashboard>
    <livewire:vapor-environment-metrics-tile tileName="My Production Website" position="a1:a3" />
    <livewire:vapor-cache-metrics-tile tileName="My Cache Instance" position="a4:a5" />
    <livewire:vapor-database-metrics-tile tileName="My Database" position="a6:a7" />
</x-dashboard>
```

See below for example tiles config that should go in the `config/dashboard.php` file. The `tileName` attribute value above should match the keys in the configs below:

```php*
'tiles' => [
    'vapor_metrics' => [
        'secret' => env('VAPOR_API_TOKEN'),
        'caches' => [
            'My Cache Instance' => ['cache_id' => 222],
        ],
        'databases' => [
            'My Database' => ['database_id' => 555],
        ],
        'environments' => [
            'My Production Website' => ['project_id' => 1111],
            'My Staging Website' => ['project_id' => 1111, 'environment' => 'staging'],
        ],
    ],
],
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
