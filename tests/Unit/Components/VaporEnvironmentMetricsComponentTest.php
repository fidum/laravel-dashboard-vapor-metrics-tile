<?php

namespace Fidum\VaporMetricsTile\Tests\Unit\Components;

use Fidum\VaporMetricsTile\Components\VaporDatabaseMetricsComponent;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsComponent;
use Fidum\VaporMetricsTile\Stores\VaporEnvironmentMetricsStore;
use Fidum\VaporMetricsTile\Tests\TestCase;
use Fidum\VaporMetricsTile\VaporMetricsClient;
use Livewire\Livewire;

class VaporEnvironmentMetricsComponentTest extends TestCase
{
    public function testMount()
    {
        Livewire::test(VaporEnvironmentMetricsComponent::class, [
            'position' => 'a1:a2',
            'tileName' => 'My Env Defaults',
        ])
            ->assertSet('position', 'a1:a2')
            ->assertSet('tileName', 'My Env Defaults');
    }

    public function testRenderNoResults()
    {
        Livewire::test(VaporEnvironmentMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Changed')
            ->assertSee('My Env Changed')
            ->assertViewHas('refreshIntervalInSeconds', 60)
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">API Gateway Requests</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Web Invocations</span>')
            ->assertSeeHtml('0ms <span class="text-dimmed text-xs">Average Web Duration</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">CLI Invocations</span>')
            ->assertSeeHtml('0ms <span class="text-dimmed text-xs">Average CLI Duration</span>')
            ->assertSeeHtml('0 <span class="text-dimmed text-xs">Queue Invocations</span>')
            ->assertSeeHtml('0ms <span class="text-dimmed text-xs">Average Queue Duration</span>');
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

        Livewire::test(VaporEnvironmentMetricsComponent::class)
            ->set('position', 'a1:a2')
            ->set('tileName', 'My Env Defaults')
            ->assertSee('My Env Defaults')
            ->assertViewHas('refreshIntervalInSeconds', VaporMetricsClient::DEFAULT_REFRESH_SECONDS)
            ->assertSeeHtml('79 <span class="text-dimmed text-xs">API Gateway Requests</span>')
            ->assertSeeHtml('1,239,321 <span class="text-dimmed text-xs">Web Invocations</span>')
            ->assertSeeHtml('433ms <span class="text-dimmed text-xs">Average Web Duration</span>')
            ->assertSeeHtml('72,136 <span class="text-dimmed text-xs">CLI Invocations</span>')
            ->assertSeeHtml('234ms <span class="text-dimmed text-xs">Average CLI Duration</span>')
            ->assertSeeHtml('971,239 <span class="text-dimmed text-xs">Queue Invocations</span>')
            ->assertSeeHtml('88,292ms <span class="text-dimmed text-xs">Average Queue Duration</span>');
    }
}
