<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Stores;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;

class VaporCacheMetricsStoreTest extends TestCase
{
    public function testTileName()
    {
        $this->assertSame('vapor-cache-metrics', VaporCacheMetricsStore::tileName());
    }

    public function testMetrics()
    {
        $key = 123456;
        $data = ['expectedData' => true];

        $store = VaporCacheMetricsStore::make()->setMetrics($key, $data);

        $this->assertSame($store->metrics($key), $data);
    }
}
