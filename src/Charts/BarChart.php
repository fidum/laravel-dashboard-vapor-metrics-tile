<?php

namespace Fidum\VaporMetricsTile\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Str;

class BarChart extends Chart
{
    public function __construct(string $id, string $period)
    {
        parent::__construct();

        $this->id = 'bar_' . $id;

        $this->options([
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
                        'unit' => $this->unit($period),
                        'round' => true,
                        'displayFormats' => [
                            'minute' => 'hh:mm a',
                            'hour' => 'hh:mm a',
                            'day' => 'MMM D',
                            'week' => 'MMM D',
                        ],
                    ],
                ]],
            ],
        ], true);
    }

    public function height($height): self
    {
        $this->height = $height;

        return $this;
    }

    public function unit(string $period): string
    {
        $periodString = Str::of($period);
        $oneUnit = $periodString->startsWith('1');

        if ($periodString->endsWith('m')) {
            return 'minute';
        }

        if ($periodString->endsWith('M')) {
            return $oneUnit ? 'day' : 'week';
        }

        if ($periodString->endsWith('d') && ! $oneUnit) {
            return 'day';
        }

        return 'hour';
    }
}
