<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporCacheMetricsComponent;
use Fidum\VaporMetricsTile\Components\VaporDatabaseMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;

class VaporCacheMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        Livewire::test(VaporCacheMetricsComponent::class, [
            'position' => 'a1:a2',
            'tileName' => 'My Cache Defaults',
        ])
            ->assertSet('position', 'a1:a2')
            ->assertSet('tileName', 'My Cache Defaults');
    }

    public function testRenderNoResults()
    {
        Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Changed')
            ->assertSee('My Cache Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60)
            ->assertDontSee('Node 1')
            ->assertDontSee('Node 2')
            ->assertSeeHtml('0% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Cache Misses</span>');
    }

    public function testRenderSingleNode()
    {
        VaporCacheMetricsStore::make()->setMetrics(101, [
            'averageCacheCpuUtilization' => [42.99],
            'totalCacheHits' => [1123],
            'totalCacheMisses' => [13243],
        ]);

        Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Defaults')
            ->assertSee('My Cache Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS)
            ->assertDontSee('Node 1')
            ->assertDontSee('Node 2')
            ->assertSeeHtml('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('1,123 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->assertSeeHtml('13,243 <span class="text-dimmed text-xs">Cache Misses</span>');
    }

    public function testRenderMultipleNodes()
    {
        VaporCacheMetricsStore::make()->setMetrics(101, [
            'averageCacheCpuUtilization' => [42.99, '75.49'],
            'totalCacheHits' => [1123, 6678],
            'totalCacheMisses' => [13243, 6654],
        ]);

        Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Defaults')
            ->assertSee('My Cache Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS)
            ->assertSeeHtml('Node 1')
            ->assertSeeHtml('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('1,123 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->assertSeeHtml('13,243 <span class="text-dimmed text-xs">Cache Misses</span>')
            ->assertSeeHtml('Node 2')
            ->assertSeeHtml('75% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('6,678 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->assertSeeHtml('6,654 <span class="text-dimmed text-xs">Cache Misses</span>');
    }
}
