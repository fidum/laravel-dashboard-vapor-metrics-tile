<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\Commands\Concerns\VaporMetricsCommandTrait;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class FetchVaporEnvironmentMetricsCommand extends Command
{
    use VaporMetricsCommandTrait;

    protected $signature = 'dashboard:fetch-data-for-vapor-environment-metrics';

    protected $description = 'Fetch data for vapor environment metrics';

    public function handle()
    {
        $configs = config('dashboard.tiles.vapor_metrics.environments');

        collect($configs)->each(function (array $config, string $name) {
            $id = $config['project_id'];
            $env = Arr::get($config, 'environment', 'production');
            $period = $this->period($config);
            $secret = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($secret)->environmentMetricsRaw($id, $env, $period);
            $key = VaporEnvironmentMetricsStore::key($name);

            VaporEnvironmentMetricsStore::make()->setMetrics($key, $data);
        });
    }
}
