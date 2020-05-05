<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FetchVaporEnvironmentMetricsCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-for-vapor-environment-metrics';

    protected $description = 'Fetch data for vapor environment metrics';

    public function handle()
    {
        $configuredProjects = config('dashboard.tiles.vapor_metrics.environments');

        $environments = new Collection($configuredProjects);

        $environments->each(function (array $config, string $name) {
            $key = VaporEnvironmentMetricsStore::key($name);
            $token = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($token)->environmentMetricsRaw(
                $config['project_id'],
                Arr::get($config, 'environment', 'production'),
                Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD),
            );

            VaporEnvironmentMetricsStore::make()->setMetrics($key, $data);
        });
    }
}
