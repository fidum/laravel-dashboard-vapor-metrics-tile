<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Charts\ChartType;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsChartComponent;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Livewire\Livewire;

class VaporEnvironmentMetricsChartComponentTest extends TestCase
{
    public function testMount()
    {
        Livewire::test(VaporEnvironmentMetricsChartComponent::class, [
            'position' => 'a1:a2',
            'tileName' => 'My Env Defaults',
            'type' => 'test_type',
            'height' => '100vh',
        ])
            ->assertSet('position', 'a1:a2')
            ->assertSet('tileName', 'My Env Defaults')
            ->assertSet('type', 'test_type')
            ->assertSet('height', '100vh');
    }

    public function testRender()
    {
        Livewire::test(VaporEnvironmentMetricsChartComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Changed')
            ->set('type', ChartType::DEFAULT)
            ->assertViewHas('tileName', 'My Env Changed')
            ->assertViewHas('type', ChartType::DEFAULT)
            ->assertViewHas('refreshIntervalInSeconds', 300)
            ->assertViewHas('height', '100%');
    }
}
