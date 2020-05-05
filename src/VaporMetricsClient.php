<?php

namespace Fidum\VaporMetricsTile;

use GuzzleHttp\Exception\ClientException;
use Laravel\VaporCli\ConsoleVaporClient;
use Laravel\VaporCli\Helpers;

class VaporMetricsClient extends ConsoleVaporClient
{
    public static function make(?string $token = null): self
    {
        $token ??= config('dashboard.tiles.vapor_metrics.secret');

        if ($token) {
            Helpers::config(['token' => $token]);
        }

        return new static();
    }

    /** @throws ClientException */
    public function environmentMetricsRaw($projectId, $environment, $period): array
    {
        return $this->requestWithoutErrorHandling(
            'get',
            sprintf("/api/projects/%s/environments/%s/metrics?period=%s", $projectId, $environment, $period)
        );
    }

    /** @throws ClientException */
    public function cacheMetricsRaw($cacheId, $period): array
    {
        return $this->requestWithErrorHandling(
            'get',
            sprintf("/api/caches/%s/metrics?period=%s", $cacheId, $period)
        );
    }

    /** @throws ClientException */
    public function databaseMetricsRaw($databaseId, $period): array
    {
        return $this->requestWithoutErrorHandling(
            'get',
            sprintf("/api/databases/%s/metrics?period=%s", $databaseId, $period),
        );
    }
}
