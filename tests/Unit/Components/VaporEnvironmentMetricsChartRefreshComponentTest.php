<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Charts\ChartType;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsChartRefreshComponent;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use NunoMaduro\LaravelMojito\ViewAssertion;

class VaporEnvironmentMetricsChartRefreshComponentTest extends TestCase
{
    public function testMount()
    {
        $component = new VaporEnvironmentMetricsChartRefreshComponent('');
        $component->mount('a1:a2', 'My Env Defaults', 'test_type', '100vh');

        $this->assertSame('a1:a2', $component->position);
        $this->assertSame('My Env Defaults', $component->tileName);

        $this->assertSame('test_type', $component->type);
        $this->assertSame('100vh', $component->height);
        $this->assertSame($component->id, $component->wireId);
    }

    public function testRenderNoResults()
    {
        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporEnvironmentMetricsChartRefreshComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Changed')
            ->set('type', ChartType::DEFAULT)
            ->set('wireId', 'abc')
            ->call('render');

        $html = $result->payload['dom'];
        $wireId = $result->payload['id'];

        $result->assertEmitted('chartDataRefreshedabc')
            ->assertViewHas('period', '7d')
            ->assertViewHas('refreshIntervalInSeconds', 60)
            ->assertViewHas('wireId', 'abc')
            ->assertViewHas('height', '100%')
        ;

        (new ViewAssertion($html))
            ->contains('<div wire:id="'.$wireId.'" class="hidden" wire:poll.60s></div>')
        ;
    }

    public function testRenderSingleNode()
    {
        $now = now();

        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_defaults', [
            'averageFunctionDurationByInterval' => [
                $now->subHours(6)->toDateTimeString() => rand(100, 200),
                $now->subHours(5)->toDateTimeString() => rand(100, 200),
                $now->subHours(4)->toDateTimeString() => rand(100, 200),
                $now->subHours(3)->toDateTimeString() => rand(100, 200),
                $now->subHours(2)->toDateTimeString() => rand(100, 200),
                $now->subHours(1)->toDateTimeString() => rand(100, 200),
            ],
        ]);

        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporEnvironmentMetricsChartRefreshComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Defaults')
            ->set('type', ChartType::DEFAULT)
            ->call('render');

        $html = $result->payload['dom'];
        $wireId = $result->payload['id'];

        $result->assertEmitted('chartDataRefreshed' . $wireId)
            ->assertViewHas('period', '1d')
            ->assertViewHas('refreshIntervalInSeconds', 300)
            ->assertViewHas('wireId', $wireId)
            ->assertViewHas('height', '100%')
        ;

        (new ViewAssertion($html))
            ->contains('<div wire:id="'.$wireId.'" class="hidden" wire:poll.300s></div>');
    }
}