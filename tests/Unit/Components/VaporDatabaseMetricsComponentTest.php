<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporDatabaseMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporDatabaseMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;

class VaporDatabaseMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        Livewire::test(VaporDatabaseMetricsComponent::class, [
            'position' => 'a1:a2',
            'tileName' => 'My DB Defaults',
        ])
            ->assertSet('position', 'a1:a2')
            ->assertSet('tileName', 'My DB Defaults');
    }

    public function testRenderNoResults()
    {
        Livewire::test(VaporDatabaseMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My DB Changed')
            ->assertSee('My DB Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60)
            ->assertSeeHtml('0% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Average Database Connections</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Max Database Connections</span>');
    }

    public function testRenderSingleNode()
    {
        VaporDatabaseMetricsStore::make()->setMetrics(201, [
            'averageDatabaseCpuUtilization' => 42.99,
            'averageDatabaseConnections' => 1123,
            'maxDatabaseConnections' => 13243,
        ]);

        Livewire::test(VaporDatabaseMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My DB Defaults')
            ->assertSee('My DB Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS)->assertSeeHtml('43% <span class="text-dimmed text-xs">Average CPU Utilization</span>')
            ->assertSeeHtml('1,123 <span class="text-dimmed text-xs">Average Database Connections</span>')
            ->assertSeeHtml('13,243 <span class="text-dimmed text-xs">Max Database Connections</span>');
    }
}
