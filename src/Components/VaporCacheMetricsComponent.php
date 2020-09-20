<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Components\Concerns\VaporMetricsComponentTrait;
use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Livewire\Component;

class VaporCacheMetricsComponent extends Component
{
    use VaporMetricsComponentTrait;

    public string $position;

    public string $tileName;

    public function mount(string $position = '', string $tileName = '')
    {
        $this->position = $position;
        $this->tileName = $tileName;
    }

    public function render()
    {
        $config = config('dashboard.tiles.vapor_metrics.caches.'.$this->tileName) ?? [];
        $key = $config['cache_id'] ?? 0;

        return view('dashboard-vapor-metrics-tiles::cache.tile', [
            'data' => VaporCacheMetricsStore::make()->metrics($key),
            'refreshIntervalInSeconds' => $this->refreshIntervalInSeconds($config),
        ]);
    }
}
