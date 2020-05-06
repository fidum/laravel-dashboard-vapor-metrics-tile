<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Stores;

use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Spatie\Dashboard\Models\Tile;

class VaporEnvironmentMetricsStoreTest extends TestCase
{
    public function testTileName()
    {
        $this->assertSame('vapor-environment-metrics', VaporEnvironmentMetricsStore::tileName());
    }

    public function testKey()
    {
        $this->assertSame('123456', VaporEnvironmentMetricsStore::key(123456));
        $this->assertSame('my_website', VaporEnvironmentMetricsStore::key('My Website'));
        $this->assertSame('a_b_c', VaporEnvironmentMetricsStore::key('A B C'));
    }

    public function testMetrics()
    {
        $key = 'random_key';
        $data = ['expectedData' => true];

        $store = VaporEnvironmentMetricsStore::make()->setMetrics($key, $data);

        $this->assertSame($store->metrics($key), $data);
    }
}
