<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Charts\ChartType;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsChartComponent;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;

class VaporEnvironmentMetricsChartComponentTest extends TestCase
{
    public function testMount()
    {
        $component = new VaporEnvironmentMetricsChartComponent('');
        $component->mount('a1:a2', 273, 'My Env Defaults', 'test_type', '100vh');

        $this->assertSame('a1:a2', $component->position);
        $this->assertSame(273, $component->refreshIntervalInSeconds);
        $this->assertSame('My Env Defaults', $component->tileName);
        $this->assertSame('test_type', $component->type);
        $this->assertSame('100vh', $component->height);
    }

    public function testRender()
    {
        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporEnvironmentMetricsChartComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Changed')
            ->set('type', ChartType::DEFAULT)
            ->call('render');

        $result
            ->assertViewHas('tileName', 'My Env Changed')
            ->assertViewHas('type', ChartType::DEFAULT)
            ->assertViewHas('refreshIntervalInSeconds', 300)
            ->assertViewHas('height', '100%');
    }
}
