<?php

namespace Fidum\VaporMetricsTile\Tests\Feature\Commands;

use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Spatie\Dashboard\Models\Tile;

class FetchVaporDatabaseMetricsCommandTest extends TestCase
{
    public function testExecute()
    {
        $data = ['expectedData' => true];
        $this->mock(VaporMetricsClient::class)->expects('databaseMetricsRaw')->twice()->andReturn($data);

        $this->artisan('dashboard:fetch-data-for-vapor-database-metrics')
            ->assertExitCode(0)
            ->execute();

        $this->assertSame(1, Tile::count());

        $this->assertSame(Tile::first()->only(['name', 'data']), [
            'name' => VaporDatabaseMetricsStore::tileName(),
            'data' => [
                "metrics:201" => $data,
                "metrics:202" => $data,
            ],
        ]);
    }
}
