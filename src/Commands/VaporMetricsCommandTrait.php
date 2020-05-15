<?php

namespace Fidum\VaporMetricsTile\Commands;

use Fidum\VaporMetricsTile\VaporMetricsClient;

trait VaporMetricsCommandTrait
{
    private function period(array $tileConfig): string
    {
        return $tileConfig['period']
            ?? config('dashboard.tiles.vapor_metrics.period')
            ?? VaporMetricsClient::DEFAULT_PERIOD;
    }
}
