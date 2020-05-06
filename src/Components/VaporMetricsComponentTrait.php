<?php

namespace Fidum\VaporMetricsTile\Components;

use Fidum\VaporMetricsTile\VaporMetricsClient;

trait VaporMetricsComponentTrait
{
    private function refreshIntervalInSeconds(array $tileConfig): int
    {
        return $tileConfig['refresh_interval_in_seconds']
            ?? config('dashboard.tiles.vapor_metrics.period')
            ?? VaporMetricsClient::DEFAULT_REFRESH_SECONDS;
    }

    private function period(array $tileConfig): string
    {
        return $tileConfig['period']
            ?? config('dashboard.tiles.vapor_metrics.period')
            ?? VaporMetricsClient::DEFAULT_PERIOD;
    }
}
