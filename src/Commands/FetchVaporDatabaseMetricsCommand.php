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
        $configs = config('dashboard.tiles.vapor_metrics.databases');

        collect($configs)->each(function (array $config) {
            $id = $config['database_id'];
            $period = Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD);
            $secret = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($secret)->databaseMetricsRaw($id, $period);

            VaporDatabaseMetricsStore::make()->setMetrics($id, $data);
        });
    }
}
