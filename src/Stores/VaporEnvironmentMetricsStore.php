<?php

namespace Fidum\VaporMetricsTile\Stores;

class VaporEnvironmentMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function tileName(): string
    {
        return 'vapor-environment-metrics';
    }
}
