<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\Commands\Concerns\VaporMetricsCommandTrait;
use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class FetchVaporCacheMetricsCommand extends Command
{
    use VaporMetricsCommandTrait;

    protected $signature = 'dashboard:fetch-data-for-vapor-cache-metrics';

    protected $description = 'Fetch data for vapor cache metrics';

    public function handle()
    {
        $configs = config('dashboard.tiles.vapor_metrics.caches');

        collect($configs)->each(function (array $config) {
            $id = $config['cache_id'];
            $period = $this->period($config);
            $secret = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($secret)->cacheMetricsRaw($id, $period);

            VaporCacheMetricsStore::make()->setMetrics($id, $data);
        });
    }
}
