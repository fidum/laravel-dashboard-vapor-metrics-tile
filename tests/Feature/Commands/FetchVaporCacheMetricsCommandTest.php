<?php

namespace Fidum\VaporMetricsTile\Tests\Feature\Commands;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Spatie\Dashboard\Models\Tile;

class FetchVaporCacheMetricsCommandTest extends TestCase
{
    public function testExecute()
    {
        $data = ['expectedData' => true];
        $this->mock(VaporMetricsClient::class)->expects('cacheMetricsRaw')->twice()->andReturn($data);

        $this->artisan('dashboard:fetch-data-for-vapor-cache-metrics')
            ->assertExitCode(0)
            ->execute();

        $this->assertSame(1, Tile::count());

        $this->assertSame(Tile::first()->only(['name', 'data']), [
            'name' => VaporCacheMetricsStore::tileName(),
            'data' => [
                'metrics:101' => $data,
                'metrics:102' => $data,
            ],
        ]);
    }
}
