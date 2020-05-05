<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
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
        $config = config('dashboard.tiles.vapor-metrics.caches.' . $this->tileName) ?? [];

        $key = VaporCacheMetricsStore::key($this->tileName, $config);

        return view('dashboard-vapor-metrics-tiles::environment.tile', [
            'data' => VaporCacheMetricsStore::make()->metrics($key),
            'period' => Arr::get($config, 'period', '1d'),
            'refreshIntervalInSeconds' => Arr::get($config, 'refresh_interval_in_seconds', 300),
        ]);
    }
}
