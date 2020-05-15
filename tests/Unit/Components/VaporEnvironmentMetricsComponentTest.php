<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;
use Livewire\Testing\TestableLivewire;
use NunoMaduro\LaravelMojito\ViewAssertion;

class VaporEnvironmentMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        $component = new VaporEnvironmentMetricsComponent('');
        $component->mount('a1:a2', 'My Env Defaults');

        $this->assertSame($component->position, 'a1:a2');
        $this->assertSame($component->tileName, 'My Env Defaults');
    }

    public function testRenderNoResults()
    {
        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporEnvironmentMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Changed')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My Env Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60);

        (new ViewAssertion($html))
            ->contains('0 <span class="text-dimmed text-xs">API Gateway Requests</span>')
            ->contains('0 <span class="text-dimmed text-xs">Web Invocations</span>')
            ->contains('0ms <span class="text-dimmed text-xs">Average Web Duration</span>')
            ->contains('0 <span class="text-dimmed text-xs">CLI Invocations</span>')
            ->contains('0ms <span class="text-dimmed text-xs">Average CLI Duration</span>')
            ->contains('0 <span class="text-dimmed text-xs">Queue Invocations</span>')
            ->contains('0ms <span class="text-dimmed text-xs">Average Queue Duration</span>')
        ;
    }

    public function testRenderSingleNode()
    {
        VaporEnvironmentMetricsStore::make()->setMetrics('my_env_defaults', [
            'totalRestApiRequests' => 79.49,
            'totalFunctionInvocations' => 1239320.5,
            'averageFunctionDuration' => 432.51,
            'totalCliFunctionInvocations' => 72136,
            'averageCliFunctionDuration' => 234,
            'totalQueueFunctionInvocations' => 971239,
            'averageQueueFunctionDuration' => 88292.23,
        ]);

        /** @var TestableLivewire $result */
        $result = Livewire::test(VaporEnvironmentMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Defaults')
            ->call('render');

        $html = $result->payload['dom'];

        $result->assertSee('My Env Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS);

        (new ViewAssertion($html))
            ->contains('79 <span class="text-dimmed text-xs">API Gateway Requests</span>')
            ->contains('1,239,321 <span class="text-dimmed text-xs">Web Invocations</span>')
            ->contains('433ms <span class="text-dimmed text-xs">Average Web Duration</span>')
            ->contains('72,136 <span class="text-dimmed text-xs">CLI Invocations</span>')
            ->contains('234ms <span class="text-dimmed text-xs">Average CLI Duration</span>')
            ->contains('971,239 <span class="text-dimmed text-xs">Queue Invocations</span>')
            ->contains('88,292ms <span class="text-dimmed text-xs">Average Queue Duration</span>')
        ;
    }
}
