<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Charts\ChartType;
use Livewire\Component;

class VaporEnvironmentMetricsChartComponent extends Component
{
    use VaporMetricsComponentTrait;

    public string $height;

    public string $position;

    public int $refreshIntervalInSeconds;

    public ?string $tileName;

    public ?string $type;

    public function mount(
        string $position = '',
        int $refreshIntervalInSeconds = null,
        string $tileName = null,
        string $type = null,
        string $height = '100%'
    ) {
        $this->height = $height;
        $this->position = $position;
        $this->tileName = $tileName ?? $this->tileName ?? '';
        $this->type = $type ?? $this->type ?? ChartType::DEFAULT;

        $config = config('dashboard.tiles.vapor_metrics.environments.'.$this->tileName) ?? [];
        $this->refreshIntervalInSeconds = $refreshIntervalInSeconds ?? $this->refreshIntervalInSeconds($config);
    }

    public function render()
    {
        return view('dashboard-vapor-metrics-tiles::environment.chart');
    }
}
