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

    public string $type;

    public string $wireId;

    public function mount(
        string $position = '',
        string $tileName = '',
        string $type = null,
        string $height = '100%',
        string $wireId = null
    ) {
        $this->height = $height;
        $this->position = $position;
        $this->tileName = $tileName;
        $this->type = $type ?? ChartType::DEFAULT;
        $this->wireId = $wireId ?? $this->id;
    }

    public function render()
    {
        return view('dashboard-vapor-metrics-tiles::environment.chart', $this->viewData());
    }

    protected function chart(string $period): BarChart
    {
        $key = VaporEnvironmentMetricsStore::key($this->tileName);
        $metrics = VaporEnvironmentMetricsStore::make()->metrics($key);
        $field = ChartType::field($this->type);
        $data = collect($metrics[$field] ?? []);

        $dataset = $data->map(fn ($metric, $date) => [
            'x' => $date,
            'y' => number_format($metric, 0, '.', ''),
        ])->values();

        $chart = new BarChart($this->wireId, $period);

        $chart
            ->height($this->height)
            ->loader(false)
            ->labels($data->keys())
            ->dataset(ChartType::label($this->tileName, $this->type), 'bar', $dataset)
            ->backgroundColor('#848584');

        return $chart;
    }

    protected function viewData(): array
    {
        $config = config('dashboard.tiles.vapor_metrics.environments.' . $this->tileName) ?? [];
        $period = $this->period($config);

        return [
            'wireId' => $this->wireId,
            'chart' => $this->chart($period),
            'period' => $period,
            'refreshIntervalInSeconds' => $this->refreshIntervalInSeconds($config),
        ];
    }
}
