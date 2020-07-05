<?php

namespace Fidum\VaporMetricsTile\Charts;

use Chartisan\PHP\Chartisan;
use Fidum\ChartTile\Charts\Chart;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class BarChart extends Chart
{
    public ?string $name = 'vapor_environment_metrics_chart';

    public function handler(Request $request): Chartisan
    {
        $tileName = $this->getTileName($request);
        $type = $request->get('type') ?: ChartType::DEFAULT;

        $key = VaporEnvironmentMetricsStore::key($tileName);
        $metrics = VaporEnvironmentMetricsStore::make()->metrics($key);
        $field = ChartType::field($type);
        $data = collect($metrics[$field] ?? []);

        $dataset = $data->map(fn ($metric, $date) => [
            'x' => $date,
            'y' => number_format($metric, 0, '.', ''),
        ])->values();

        return Chartisan::build()
            ->labels($data->keys()->toArray())
            ->dataset(ChartType::label($tileName, $type), $dataset->toArray());
    }

    public function type(): string
    {
        return 'bar';
    }

    public function colors(): array
    {
        return ['#848584'];
    }

    public function options(): array
    {
        return [
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
        $tileName = $this->getTileName(app(Request::class));
        $tileConfig = config('dashboard.tiles.vapor_metrics.environments.'.$tileName) ?? [];

        return $tileConfig['period']
            ?? config('dashboard.tiles.vapor_metrics.period')
            ?? VaporMetricsClient::DEFAULT_PERIOD;
    }

    private function unit(): string
    {
        $periodString = Str::of($this->period());

        $oneUnit = filter_var($periodString, FILTER_SANITIZE_NUMBER_INT) === '1';

        $availableUnits = [
            'm' => $oneUnit ? 'second' : 'minute',
            'h' => $oneUnit ? 'minute' : 'hour',
            'd' => $oneUnit ? 'hour' : 'day',
            'M' => $oneUnit ? 'day' : 'week',
        ];

        $unit = (string) $periodString->substr(-1);

        return $availableUnits[$unit] ?? 'hour';
    }

    private function getTileName(Request $request): string
    {
        $defaultTile = Arr::get(array_keys(config('dashboard.tiles.vapor_metrics.environments', [])), 0, '');

        return $request->get('tileName') ?: $defaultTile;
    }
}
