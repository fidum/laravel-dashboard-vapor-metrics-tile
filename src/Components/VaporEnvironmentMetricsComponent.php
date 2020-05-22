<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Livewire\Component;

class VaporEnvironmentMetricsComponent extends Component
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
        $config = config('dashboard.tiles.vapor_metrics.environments.'.$this->tileName) ?? [];
        $key = VaporEnvironmentMetricsStore::key($this->tileName);

        return view('dashboard-vapor-metrics-tiles::environment.tile', [
            'data' => VaporEnvironmentMetricsStore::make()->metrics($key),
            'refreshIntervalInSeconds' => $this->refreshIntervalInSeconds($config),
        ]);
    }
}
