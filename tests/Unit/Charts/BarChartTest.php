<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Charts;

use Carbon\Carbon;
use Fidum\VaporMetricsTile\Charts\BarChart;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;

class BarChartTest extends TestCase
{
    public function testChartEmptyData()
    {
        $chart = app(BarChart::class);
        $chartisan = $chart->handler(Request::merge([]));
        $object = $chartisan->toObject();

        $this->assertSame([], $object->chart->labels);
        $this->assertSame($this->expectedOptions('hour'), $chart->options());
        $this->assertSame([], $object->datasets[0]->values);
    }

    public function testChartDefaults()
    {
        $data = $this->data();

        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_defaults', [
            'averageFunctionDurationByInterval' => $data->toArray(),
        ]);

        $chart = app(BarChart::class);
        $chartisan = $chart->handler(Request::merge([]));
        $object = $chartisan->toObject();

        $this->assertSame($data->keys()->toArray(), $object->chart->labels);
        $this->assertSame($this->expectedOptions('hour'), $chart->options());

        $this->assertSame(
            $data->map(fn ($y, $x) => compact('x', 'y'))->values()->toArray(),
            $object->datasets[0]->values,
        );
    }

    public function testChartWithSettings()
    {
        $data = $this->data();

        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_changed', [
            'totalCliFunctionInvocationsByInterval' => $data->toArray(),
        ]);

        $chart = app(BarChart::class);
        $chartisan = $chart->handler(Request::merge(['tileName' => 'My Env Changed', 'type' => 'cli-invocations-total']));
        $object = $chartisan->toObject();

        $this->assertSame($data->keys()->toArray(), $object->chart->labels);
        $this->assertSame($this->expectedOptions('day'), $chart->options());

        $this->assertSame(
            $data->map(fn ($y, $x) => compact('x', 'y'))->values()->toArray(),
            $object->datasets[0]->values,
        );
    }

    /** @dataProvider unitProvider */
    public function testUnit(string $period, string $expectedUnit)
    {
        config()->set('dashboard.tiles.vapor_metrics.period', $period);

        $chart = app(BarChart::class);

        $this->assertSame($this->expectedOptions($expectedUnit), $chart->options());
    }

    public function unitProvider(): array
    {
        //1m, 5m, 30m, 1h, 8h, 1d (default), 3d, 7d, 1M
        return [
            ['1m', 'second'],
            ['12m', 'minute'],
            ['1h', 'minute'],
            ['12h', 'hour'],
            ['1d', 'hour'],
            ['12d', 'day'],
            ['1M', 'day'],
            ['12M', 'week'],
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
