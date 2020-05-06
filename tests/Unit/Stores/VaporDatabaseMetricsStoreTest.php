<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Stores;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Spatie\Dashboard\Models\Tile;

class VaporDatabaseMetricsStoreTest extends TestCase
{
    public function testTileName()
    {
        $this->assertSame('vapor-database-metrics', VaporDatabaseMetricsStore::tileName());
    }

    public function testMetrics()
    {
        $key = 123456;
        $data = ['expectedData' => true];

        $store = VaporDatabaseMetricsStore::make()->setMetrics($key, $data);

        $this->assertSame($store->metrics($key), $data);
    }
}
