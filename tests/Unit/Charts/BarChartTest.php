<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Charts;

use Carbon\Carbon;
use Fidum\VaporMetricsTile\Charts\BarChart;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\DataProvider;

class BarChartTest extends TestCase
{
    public function testChartEmptyData()
    {
        $factory = BarChart::make([]);
        $chart = $factory->chart();

        $this->assertSame([], $chart->labels);
        $this->assertSame($this->expectedOptions('hour'), $chart->options);
        $this->assertSame([], $chart->datasets[0]->values);
    }

    public function testChartDefaults()
    {
        $data = $this->data();

        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_defaults', [
            'averageFunctionDurationByInterval' => $data->toArray(),
        ]);

        $factory = BarChart::make([]);
        $chart = $factory->chart();

        $this->assertSame($data->keys()->toArray(), $chart->labels);
        $this->assertSame($this->expectedOptions('hour'), $chart->options);

        $this->assertSame(
            $data->map(fn ($y, $x) => compact('x', 'y'))->values()->toArray(),
            $chart->datasets[0]->values,
        );
    }

    public function testChartWithSettings()
    {
        $data = $this->data();

        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_changed', [
            'totalCliFunctionInvocationsByInterval' => $data->toArray(),
        ]);

        $factory = BarChart::make(['tileName' => 'My Env Changed', 'type' => 'cli-invocations-total']);
        $chart = $factory->chart();

        $this->assertSame($data->keys()->toArray(), $chart->labels);
        $this->assertSame($this->expectedOptions('day'), $chart->options);

        $this->assertSame(
            $data->map(fn ($y, $x) => compact('x', 'y'))->values()->toArray(),
            $chart->datasets[0]->values,
        );
    }

    #[DataProvider('unitProvider')]
    public function testUnit(string $period, string $expectedUnit)
    {
        config()->set('dashboard.tiles.vapor_metrics.period', $period);

        $factory = BarChart::make([]);
        $chart = $factory->chart();

        $this->assertSame($this->expectedOptions($expectedUnit), $chart->options);
    }

    public static function unitProvider(): array
    {
        //1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
        return [
            ['1m', 'second'],
            ['5m', 'minute'],
            ['1h', 'minute'],
            ['8h', 'hour'],
            ['1d', 'hour'],
            ['7d', 'day'],
            ['1M', 'day'],
            ['2M', 'week'],
        ];
    }

    private function data(): Collection
    {
        $now = Carbon::now();

        return collect([
            $now->subHours(6)->toDateTimeString() => (string) rand(100, 200),
            $now->subHours(5)->toDateTimeString() => (string) rand(100, 200),
            $now->subHours(4)->toDateTimeString() => (string) rand(100, 200),
            $now->subHours(3)->toDateTimeString() => (string) rand(100, 200),
            $now->subHours(2)->toDateTimeString() => (string) rand(100, 200),
            $now->subHours(1)->toDateTimeString() => (string) rand(100, 200),
        ]);
    }

    private function expectedOptions(string $unit): array
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
                        'unit' => $unit,
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
}
