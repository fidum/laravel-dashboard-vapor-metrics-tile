<?php

namespace Fidum\VaporMetricsTile\Tests;

use ConsoleTVs\Charts\ChartsServiceProvider;
use Fidum\ChartTile\ChartTileServiceProvider;
use Fidum\VaporMetricsTile\VaporMetricsTileServiceProvider;
use Livewire\LivewireServiceProvider;
use NunoMaduro\LaravelMojito\MojitoServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\Dashboard\DashboardServiceProvider;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function setUpDatabase()
    {
        $this->loadMigrationsFrom(['--path' => __DIR__.'/Fixtures/database/migrations']);
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('dashboard.tiles.vapor_metrics', [
            'secret' => 'default-secret',
            'caches' => [
                'My Cache Defaults' => ['cache_id' => 101],
                'My Cache Changed' => [
                    'cache_id' => 102,
                    'period' => '7d', // optional: 1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
                    'refresh_interval_in_seconds' => 60, // optional: override individual tile
                    'secret' => 'cache-secret', // :optional: override individual tile
                ],
            ],
            'databases' => [
                'My DB Defaults' => ['database_id' => 201],
                'My DB Changed' => [
                    'database_id' => 202,
                    'period' => '7d',
                    'refresh_interval_in_seconds' => 60,
                    'secret' => 'db-secret',
                ],
            ],
            'environments' => [
                'My Env Defaults' => ['project_id' => 301],
                'My Env Changed' => [
                    'project_id' => 302,
                    'environment' => 'staging',
                    'period' => '7d',
                    'refresh_interval_in_seconds' => 60,
                    'secret' => 'env-secret',
                ],
            ],
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ChartsServiceProvider::class,
            ChartTileServiceProvider::class,
            DashboardServiceProvider::class,
            LivewireServiceProvider::class,
            MojitoServiceProvider::class,
            VaporMetricsTileServiceProvider::class,
        ];
    }
}
