<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FetchVaporDatabaseMetricsCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-for-vapor-database-metrics';

    protected $description = 'Fetch data for vapor database metrics';

    public function handle()
    {
        $configuredProjects = config('dashboard.tiles.vapor_metrics.databases');

        $databases = new Collection($configuredProjects);

        $databases->each(function (array $config, $name) {
            $key = $config['database_id'];
            $token = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($token)->databaseMetricsRaw(
                $key,
                Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD),
            );

            VaporDatabaseMetricsStore::make()->setMetrics($key, $data);
        });
    }
}
