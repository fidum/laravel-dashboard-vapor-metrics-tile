<?php

namespace Fidum\VaporMetricsTile\Tests\Feature\Commands;

use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Spatie\Dashboard\Models\Tile;

class FetchVaporEnvironmentMetricsCommandTest extends TestCase
{
    public function testExecute()
    {
        $data = ['expectedData' => true];
        $this->mock(VaporMetricsClient::class)->expects('environmentMetricsRaw')->twice()->andReturn($data);

        $this->artisan('dashboard:fetch-data-for-vapor-environment-metrics')
            ->assertExitCode(0)
            ->execute();

        $this->assertDatabaseCount('dashboard_tiles', 1);

        $this->assertSame(Tile::first()->only(['name', 'data']), [
            'name' => VaporEnvironmentMetricsStore::tileName(),
            'data' => [
                "metrics:my_env_defaults" => $data,
                "metrics:my_env_changed" => $data,
            ],
        ]);
    }
}
