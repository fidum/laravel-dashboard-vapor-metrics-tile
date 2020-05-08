<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\Charts\BarChart;

class VaporEnvironmentMetricsChartRefreshComponent extends VaporEnvironmentMetricsChartComponent
{
    public function render()
    {
        return view('dashboard-vapor-metrics-tiles::environment.chart_refresh', $this->viewData());
    }

    protected function chart(string $period): BarChart
    {
        $chart = parent::chart($period);

        $this->emit('polledEvent' . $this->wireId, [
            'labels' => $chart->labels,
            'datasets' => $chart->formatDatasets(),
            'options' => $chart->options,
        ]);

        return $chart;
    }
}
