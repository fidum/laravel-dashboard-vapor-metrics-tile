<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporDatabaseMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use NunoMaduro\LaravelMojito\ViewAssertion;

class VaporDatabaseMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        $component = new VaporDatabaseMetricsComponent('');
        $component->mount('a1:a2', 'My DB Defaults');

        $this->assertSame($component->position, 'a1:a2');
        $this->assertSame($component->tileName, 'My DB Defaults');
    }

    public function testRenderNoResults()
    {
        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporDatabaseMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My DB Changed')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My DB Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60);

        (new ViewAssertion($html))
            ->contains('0% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('0 <span class="text-dimmed text-xs">Average Database Connections</span>')
            ->contains('0 <span class="text-dimmed text-xs">Max Database Connections</span>')
        ;
    }

    public function testRenderSingleNode()
    {
        VaporDatabaseMetricsStore::make()->setMetrics(201, [
            'averageDatabaseCpuUtilization' => 42.99,
            'averageDatabaseConnections' => 1123,
            'maxDatabaseConnections' => 13243,
        ]);

        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporDatabaseMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My DB Defaults')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My DB Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS);

        (new ViewAssertion($html))
            ->contains('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->contains('1,123 <span class="text-dimmed text-xs">Average Database Connections</span>')
            ->contains('13,243 <span class="text-dimmed text-xs">Max Database Connections</span>')
        ;
    }
}
