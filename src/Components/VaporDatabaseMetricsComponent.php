<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Livewire\Component;

class VaporDatabaseMetricsComponent extends Component
{
    use VaporMetricsComponentTrait;

    public string $position;

    public string $tileName;

    public function mount(string $position, string $tileName)
    {
        $this->position = $position;
        $this->tileName = $tileName;
    }

    public function render()
    {
        $config = config('dashboard.tiles.vapor_metrics.databases.' . $this->tileName) ?? [];
        $key = $config['database_id'] ?? 0;

        return view('dashboard-vapor-metrics-tiles::database.tile', [
            'data' => VaporDatabaseMetricsStore::make()->metrics($key),
            'period' => $this->period($config),
            'refreshIntervalInSeconds' => $this->refreshIntervalInSeconds($config),
        ]);
    }
}
