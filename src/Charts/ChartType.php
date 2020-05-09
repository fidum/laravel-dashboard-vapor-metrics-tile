<?php

namespace Fidum\VaporMetricsTile\Charts;

use Illuminate\Support\Arr;

class ChartType
{
    const DEFAULT = 'http-requests-avg-duration';

    const TYPES = [
        'cli-avg-duration' => [
            'label' => 'Average CLI Invocation Duration (ms)',
            'field' => 'averageCliFunctionDurationByInterval',
        ],
        'cli-invocations-total' => [
            'label' => 'CLI Invocations',
            'field' => 'totalCliFunctionInvocationsByInterval',
        ],
        'http-requests-avg-duration' => [
            'label' => 'Average HTTP Request Duration (ms)',
            'field' => 'averageFunctionDurationByInterval',
        ],
        'http-requests-total' => [
            'label' => 'HTTP Requests',
            'field' => 'totalFunctionInvocationsByInterval',
        ],
        'queue-avg-duration' => [
            'label' => 'Average Queue Invocation Duration (ms)',
            'field' => 'averageQueueFunctionDurationByInterval',
        ],
        'queue-invocations-total' => [
            'label' => 'Queue Invocations',
            'field' => 'totalQueueFunctionInvocationsByInterval',
        ],
    ];

    public static function field(string $type = self::DEFAULT): string
    {
        return Arr::get(static::TYPES, $type, [])['field'] ?? static::defaultField();
    }

    public static function label(string $tileName, string $type = self::DEFAULT): string
    {
        $label = Arr::get(static::TYPES, $type, [])['label'] ?? static::defaultLabel();

        return "$label ($tileName)";
    }

    private static function defaultField()
    {
        return static::TYPES[static::DEFAULT]['field'];
    }

    private static function defaultLabel()
    {
        return static::TYPES[static::DEFAULT]['label'];
    }
}
