<?php

namespace Fidum\VaporMetricsTile\Components\Concerns;

use Fidum\VaporMetricsTile\VaporMetricsClient;

trait VaporMetricsComponentTrait
{
    private function refreshIntervalInSeconds(array $tileConfig): int
    {
        return $tileConfig['refresh_interval_in_seconds']
            ?? config('dashboard.tiles.vapor_metrics.refresh_interval_in_seconds')
            ?? VaporMetricsClient::DEFAULT_REFRESH_SECONDS;
    }
}
