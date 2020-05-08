<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Charts\BarChart;
use Fidum\VaporMetricsTile\Charts\ChartType;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Livewire\Component;

class VaporEnvironmentMetricsChartComponent extends Component
{
    use VaporMetricsComponentTrait;

    public string $height;

    public string $position;

    public string $tileName;

    public string $title;

    public string $type;

    public function mount(
        string $position = '',
        string $tileName = '',
        string $type = null,
        string $height = '100%'
    ) {
        $this->height = $height;
        $this->position = $position;
        $this->tileName = $tileName;
        $this->title = $title ?? $tileName ?? 'Metrics';
        $this->type = $type ?? ChartType::DEFAULT;
    }

    public function render()
    {
        $this->emit('polledEvent' . $this->id);
        $config = config('dashboard.tiles.vapor_metrics.environments.' . $this->tileName) ?? [];
        $key = VaporEnvironmentMetricsStore::key($this->tileName);
        $metrics = VaporEnvironmentMetricsStore::make()->metrics($key);
        $period = $this->period($config);
        $field = ChartType::field($this->type);

        $data = collect($metrics[$field] ?? []);
        $dataset = $data->map(fn ($metric, $date) => [
            'x' => $date,
            'y' => number_format($metric, 0, '.', ''),
        ])->values();

        $chart = new BarChart($this->id, $period);

        $chart
            ->height($this->height)
            ->loader(false)
            ->labels($data->keys())
            ->dataset(ChartType::label($this->tileName, $this->type), 'bar', $dataset)
            ->backgroundColor('#848584');

        return view('dashboard-vapor-metrics-tiles::environment.chart', [
            'wireId' => $this->id,
            'chart' => $chart,
            'period' => $this->period($config),
            'refreshIntervalInSeconds' => $this->refreshIntervalInSeconds($config),
        ]);
    }
}
