<?php

namespace Fidum\VaporMetricsTile\Stores;

class VaporCacheMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function tileName(): string
    {
        return 'vapor-cache-metrics';
    }
}
