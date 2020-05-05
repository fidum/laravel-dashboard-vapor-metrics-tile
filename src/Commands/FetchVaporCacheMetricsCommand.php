<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class FetchVaporCacheMetricsCommand extends Command
{
    protected $signature = 'dashboard:fetch-data-for-vapor-cache-metrics';

    protected $description = 'Fetch data for vapor cache metrics';

    public function handle()
    {
        $configuredProjects = config('dashboard.tiles.vapor_metrics.caches');

        $caches = new Collection($configuredProjects);

        $caches->each(function (array $config, $name) {
            $key = $config['cache_id'];
            $token = Arr::get($config, 'secret');

            $data = VaporMetricsClient::make($token)->cacheMetricsRaw(
                $key,
                Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD),
            );

            VaporCacheMetricsStore::make()->setMetrics($key, $data);
        });
    }
}
