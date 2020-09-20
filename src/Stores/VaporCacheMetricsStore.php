<?php

namespace Fidum\VaporMetricsTile\Stores;

use Fidum\VaporMetricsTile\Stores\Concerns\VaporMetricsStoreTrait;

class VaporCacheMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function tileName(): string
    {
        return 'vapor-cache-metrics';
    }
}
