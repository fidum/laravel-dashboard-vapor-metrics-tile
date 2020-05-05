<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Illuminate\Support\Arr;
use Livewire\Component;

class VaporEnvironmentMetricsComponent extends Component
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
        $config = config('dashboard.tiles.vapor-metrics.environments.' . $this->tileName) ?? [];

        $key = VaporEnvironmentMetricsStore::key($this->tileName, $config);

        return view('tiles.vapor-environment-metrics', [
            'data' => VaporEnvironmentMetricsStore::make()->metrics($key),
            'period' => Arr::get($config, 'period', '1d'),
            'refreshIntervalInSeconds' => Arr::get($config, 'refresh_interval_in_seconds', 300),
        ]);
    }
}
