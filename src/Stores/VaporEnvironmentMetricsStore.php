<?php

namespace Fidum\VaporMetricsTile\Stores;

use Fidum\VaporMetricsTile\Stores\Concerns\VaporMetricsStoreTrait;
use Illuminate\Support\Str;

class VaporEnvironmentMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function key(string $name): string
    {
        return (string) Str::of($name)->snake()->lower();
    }

    public static function tileName(): string
    {
        return 'vapor-environment-metrics';
    }
}
