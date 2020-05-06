<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Support\Arr;
use Livewire\Component;

class VaporCacheMetricsComponent extends Component
{
    public string $position;

    public string $tileName;

    public function mount(string $position, string $tileName)
    {
        $this->position = $position;
        $this->tileName = $tileName;
    }

    public function render()
    {
        $config = config('dashboard.tiles.vapor_metrics.caches.' . $this->tileName) ?? [];
        $refresh = Arr::get($config, 'refresh_interval_in_seconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS);
        $key = Arr::get($config, 'cache_id', 0);

        return view('dashboard-vapor-metrics-tiles::cache.tile', [
            'data' => VaporCacheMetricsStore::make()->metrics($key),
            'period' => Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD),
            'refreshIntervalInSeconds' => $refresh,
        ]);
    }
}
