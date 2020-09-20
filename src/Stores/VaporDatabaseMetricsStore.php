<?php

namespace Fidum\VaporMetricsTile\Stores;

use Fidum\VaporMetricsTile\Stores\Concerns\VaporMetricsStoreTrait;

class VaporDatabaseMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function tileName(): string
    {
        return 'vapor-database-metrics';
    }
}
