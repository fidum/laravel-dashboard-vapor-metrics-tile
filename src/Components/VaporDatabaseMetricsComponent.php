<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
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
        $config = config('dashboard.tiles.vapor_metrics.databases.' . $this->tileName) ?? [];

        $key = Arr::get($config, 'database_id', 0);

        return view('dashboard-vapor-metrics-tiles::database.tile', [
            'data' => VaporDatabaseMetricsStore::make()->metrics($key),
            'period' => Arr::get($config, 'period', VaporMetricsClient::DEFAULT_PERIOD),
            'refreshIntervalInSeconds' => Arr::get($config, 'refresh_interval_in_seconds', 300),
        ]);
    }
}
