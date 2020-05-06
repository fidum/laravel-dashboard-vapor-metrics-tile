<?php

namespace Fidum\VaporMetricsTile;

use Laravel\VaporCli\ConsoleVaporClient;
use Laravel\VaporCli\Helpers;

class VaporMetricsClient extends ConsoleVaporClient
{
    public const DEFAULT_PERIOD = '1d';

    public const DEFAULT_REFRESH_SECONDS = 300;

    public static function make(?string $secret = null): self
    {
        $secret ??= config('dashboard.tiles.vapor_metrics.secret');

        if ($secret) {
            Helpers::config(['token' => $secret]);
        }

        return new static();
    }

    public function environmentMetricsRaw(int $projectId, string $environment, string $period): array
    {
        return $this->rawRequest(
            'get',
            sprintf("/api/projects/%s/environments/%s/metrics?period=%s", $projectId, $environment, $period)
        );
    }

    public function cacheMetricsRaw(int $cacheId, string $period): array
    {
        return $this->rawRequest(
            'get',
            sprintf("/api/caches/%s/metrics?period=%s", $cacheId, $period)
        );
    }

    public function databaseMetricsRaw(int $databaseId, string $period): array
    {
        return $this->rawRequest(
            'get',
            sprintf("/api/databases/%s/metrics?period=%s", $databaseId, $period),
        );
    }

    private function rawRequest(string $method, string $uri, array $json = []): array
    {
        return rescue(fn () => $this->requestWithoutErrorHandling($method, $uri, $json) ?? [], []);
    }
}
