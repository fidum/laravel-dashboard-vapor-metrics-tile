<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporCacheMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporCacheMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use NunoMaduro\LaravelMojito\ViewAssertion;

class VaporCacheMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        $component = new VaporCacheMetricsComponent('');
        $component->mount('a1:a2', 'My Cache Defaults');

        $this->assertSame($component->position, 'a1:a2');
        $this->assertSame($component->tileName, 'My Cache Defaults');
    }

    public function testRenderNoResults()
    {
        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Changed')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My Cache Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60);

        $this->assertStringNotContainsString('Node 1', $html);
        $this->assertStringNotContainsString('Node 2', $html);

        (new ViewAssertion($html))
            ->contains('0% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('0 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->contains('0 <span class="text-dimmed text-xs">Cache Misses</span>')
        ;
    }

    public function testRenderSingleNode()
    {
        VaporCacheMetricsStore::make()->setMetrics(101, [
            'averageCacheCpuUtilization' => [42.99],
            'totalCacheHits' => [1123],
            'totalCacheMisses' => [13243],
        ]);

        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Defaults')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My Cache Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS);

        $this->assertStringNotContainsString('Node 1', $html);
        $this->assertStringNotContainsString('Node 2', $html);

        (new ViewAssertion($html))
            ->contains('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('1,123 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->contains('13,243 <span class="text-dimmed text-xs">Cache Misses</span>')
        ;
    }

    public function testRenderMultipleNodes()
    {
        VaporCacheMetricsStore::make()->setMetrics(101, [
            'averageCacheCpuUtilization' => [42.99, '75.49'],
            'totalCacheHits' => [1123, 6678],
            'totalCacheMisses' => [13243, 6654],
        ]);

        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporCacheMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Cache Defaults')
            ->call('render');

        $result->assertSee('My Cache Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS);

        $assert = new ViewAssertion($result->payload['dom']);

        $assert->contains('Node 1')
            ->contains('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('1,123 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->contains('13,243 <span class="text-dimmed text-xs">Cache Misses</span>')
        ;

        $assert->contains('Node 2')
            ->contains('75% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('6,678 <span class="text-dimmed text-xs">Cache Hits</span>')
            ->contains('6,654 <span class="text-dimmed text-xs">Cache Misses</span>')
        ;
    }
}
