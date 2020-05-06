# Vapor Metrics Tile

[![Latest Version on Packagist](https://img.shields.io/packagist/v/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/fidum/laravel-dashboard-vapor-metrics-tile/run-tests?label=tests)](https://github.com/fidum/laravel-dashboard-vapor-metrics-tile/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![codecov](https://codecov.io/gh/fidum/laravel-dashboard-vapor-metrics-tile/branch/master/graph/badge.svg)](https://codecov.io/gh/fidum/laravel-dashboard-vapor-metrics-tile)
[![Total Downloads](https://img.shields.io/packagist/dt/fidum/laravel-dashboard-vapor-metrics-tile.svg?style=flat-square)](https://packagist.org/packages/fidum/laravel-dashboard-vapor-metrics-tile)
[![Twitter Follow](https://img.shields.io/twitter/follow/danmasonmp.svg?style=social)](https://twitter.com/danmasonmp)  

Displays metrics for **all** of your laravel vapor projects - caches, databases and environment metrics included! 

![Preview](docs/preview.png)

## Installation

You can install the package via composer:

```bash
composer require fidum/laravel-dashboard-vapor-metrics-tile
```

## Usage
In the `dashboard` config file, you must add this configuration in the `tiles` key. There are separate settings for 
`caches`, `databases` and `environments`.

```php
// in config/dashboard.php

return [
    // ...
    'tiles' => [
        'vapor_metrics' => [
            'secret' => env('VAPOR_API_TOKEN'), // optional: Uses `VAPOR_API_TOKEN` env by default
            'refresh_interval_in_seconds' => 300, // optional: Default: 300 seconds (5 minutes)
            'period' => '7d', // optional: 1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
            'caches' => [ // Leave empty if you don't want any cache tiles
                'My Cache Instance' => [ // Key will be used as the title of the displayed tile
                    'cache_id' => 222, // required: The id of your vapor cache instance
                    'period' => '7d', // optional: 1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
                    'refresh_interval_in_seconds' => 60, // optional: override individual tile
                    'secret' => null, // :optional: override individual tile
                ],
                'Another Cache' => ['cache_id' => 333]
            ],
            'databases' => [ // Leave empty if you don't want any database tiles
                'My Database' => [ // Key will be used as the title of the displayed tile
                    'database_id' => 555, // required: The id of your vapor database instance
                    'period' => '7d', // optional: 1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
                    'refresh_interval_in_seconds' => 60, // optional: override individual tile
                    'secret' => null, // :optional: override individual tile
                ],
                'Another Database' => ['database_id' => 444]
            ],
            'environments' => [ // Leave empty if you don't want any envrionment tiles
                'My Staging Website' => [ // Key will be used as the title of the displayed tile                
                    'project_id' => 1111, // required: The id of your vapor project
                    'environment' => 'staging', // optional: Defaults to 'production'
                    'period' => '7d', // optional: 1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
                    'refresh_interval_in_seconds' => 60, // optional: override individual tile
                    'secret' => null, // :optional: override individual tile
                ],
                'My Production Website' => ['project_id' => 1111],
            ],
        ],
    ],
];
```

In `app\Console\Kernel.php` you should schedule the below to run every `x` minutes. Only add the commands where you have configured the related tiles above. 

```php
// in app/console/Kernel.php

protected function schedule(Schedule $schedule)
{
    $schedule->command(Fidum\VaporMetricsTile\Commands\FetchVaporCacheMetricsCommand::class)->everyThirtyMinutes();
    $schedule->command(Fidum\VaporMetricsTile\Commands\FetchVaporDatabaseMetricsCommand::class)->everyThirtyMinutes();
    $schedule->command(Fidum\VaporMetricsTile\Commands\FetchVaporEnvironmentMetricsCommand::class)->everyThirtyMinutes();
}
```

In your dashboard view you can use one or all or multiple of each of these components. The `tileName` and `position` 
attributes are **required**. The `tileName` attribute value needs to match the name specified in the config:

```html
<x-dashboard>
    <livewire:vapor-environment-metrics-tile tileName="My Production Website" position="a1:a3" />
    <livewire:vapor-cache-metrics-tile tileName="My Cache Instance" position="a4:a5" />
    <livewire:vapor-database-metrics-tile tileName="My Database" position="a6:a7" />
</x-dashboard>
```
## Testing
```bash
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
