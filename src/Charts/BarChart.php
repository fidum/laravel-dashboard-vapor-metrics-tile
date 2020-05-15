<?php

namespace Fidum\VaporMetricsTile\Charts;

use Fidum\ChartTile\Charts\Chart;
use Fidum\ChartTile\Contracts\ChartFactory;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BarChart implements ChartFactory
{
    private string $tileName;
    private string $chartType;

    public function __construct(string $tileName, string $chartType)
    {
        $this->tileName = $tileName;
        $this->chartType = $chartType;
    }

    public static function make(array $settings): ChartFactory
    {
        $defaultTile = Arr::get(array_keys(config('dashboard.tiles.vapor_metrics.environments', [])), 0, '');

        return new static(
            Arr::get($settings, 'tileName', $defaultTile),
            Arr::get($settings, 'type', ChartType::DEFAULT)
        );
    }

    public function chart(): Chart
    {
        $chart = new Chart();

        $key = VaporEnvironmentMetricsStore::key($this->tileName);
        $metrics = VaporEnvironmentMetricsStore::make()->metrics($key);
        $field = ChartType::field($this->chartType);
        $data = collect($metrics[$field] ?? []);

        $dataset = $data->map(fn ($metric, $date) => [
            'x' => $date,
            'y' => number_format($metric, 0, '.', ''),
        ])->values();

        $chart
            ->loader(false)
            ->labels($data->keys())
            ->options($this->options(), true)
            ->dataset(ChartType::label($this->tileName, $this->chartType), 'bar', $dataset)
            ->backgroundColor('#848584');

        return $chart;
    }

    private function options(): array
    {
        return [
            'animation' => [
                'duration' => 0,
            ],
            'responsive' => true,
            'maintainAspectRatio' => false,
            'legend' => [
                'display' => true,
                'labels' => [
                    'boxWidth' => 0,
                ],
            ],
            'scales' => [
                'xAxes' => [[
                    'display' => true,
                    'offset' => true,
                    'type' => 'time',
                    'ticks' => [
                        'source' => 'auto',
                        'maxRotation' => 0,
                    ],
                    'time' => [
                        'unit' => $this->unit(),
                        'round' => true,
                        'displayFormats' => [
                            'second' => 'hh:mm:ss',
                            'minute' => 'hh:mm a',
                            'hour' => 'hh:mm a',
                            'day' => 'MMM D',
                            'week' => 'MMM D',
                        ],
                    ],
                ]],
            ],
        ];
    }

    private function period(): string
    {
        $tileConfig = config('dashboard.tiles.vapor_metrics.environments.' . $this->tileName) ?? [];

        return $tileConfig['period']
            ?? config('dashboard.tiles.vapor_metrics.period')
            ?? VaporMetricsClient::DEFAULT_PERIOD;
    }

    private function unit(): string
    {
        $periodString = Str::of($this->period());

        $oneUnit = $periodString->startsWith('1');

        $availableUnits = [
            'm' => $oneUnit ? 'second' : 'minute',
            'h' => $oneUnit ? 'minute' : 'hour',
            'd' => $oneUnit ? 'hour' : 'day',
            'M' => $oneUnit ? 'day' : 'week',
        ];

        $unit = (string) $periodString->substr(-1);

        return $availableUnits[$unit] ?? 'hour';
    }
}
