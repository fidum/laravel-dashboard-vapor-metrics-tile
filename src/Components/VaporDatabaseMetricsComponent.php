<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Illuminate\Support\Arr;
use Livewire\Component;

class VaporDatabaseMetricsComponent extends Component
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
        $config = config('dashboard.tiles.vapor-metrics.databases.' . $this->tileName) ?? [];

        $key = VaporDatabaseMetricsStore::key($this->tileName, $config);

        return view('tiles.vapor-cache-metrics', [
            'data' => VaporDatabaseMetricsStore::make()->metrics($key),
            'period' => Arr::get($config, 'period', '1d'),
            'refreshIntervalInSeconds' => Arr::get($config, 'refresh_interval_in_seconds', 300),
        ]);
    }
}
