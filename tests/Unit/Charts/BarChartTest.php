<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Charts;

use Fidum\VaporMetricsTile\Charts\BarChart;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Illuminate\Support\Str;

class BarChartTest extends TestCase
{
    public function testConstruct()
    {
        $id = Str::random();
        $chart = new BarChart($id, '7d');

        $this->assertSame('bar_'.$id, $chart->id);
        $this->assertSame($this->expectedOptions('day'), $chart->options);
    }

    public function testHeight()
    {
        $chart = new BarChart('', '7d');

        $this->assertSame(400, $chart->height);

        $chart->height(600);
        $this->assertSame(600, $chart->height);

        $chart->height('100vh');
        $this->assertSame('100vh', $chart->height);
    }

    /** @dataProvider provider */
    public function testUnit(string $period, string $expectedUnit)
    {
        $chart = new BarChart('', $period);

        $this->assertSame($this->expectedOptions($expectedUnit), $chart->options);
        $this->assertSame($expectedUnit, $chart->unit($period));
    }

    public function provider(): array
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
