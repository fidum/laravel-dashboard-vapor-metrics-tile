<?php

namespace Fidum\VaporMetricsTile\Stores;

class VaporDatabaseMetricsStore
{
    use VaporMetricsStoreTrait;

    public static function tileName(): string
    {
        return 'vapor-database-metrics';
    }
}
