<?php

namespace Fidum\VaporMetricsTile;

use Fidum\VaporMetricsTile\Commands\FetchVaporCacheMetricsCommand;
use Fidum\VaporMetricsTile\Commands\FetchVaporDatabaseMetricsCommand;
use Fidum\VaporMetricsTile\Commands\FetchVaporEnvironmentMetricsCommand;
use Fidum\VaporMetricsTile\Components\VaporCacheMetricsComponent;
use Fidum\VaporMetricsTile\Components\VaporDatabaseMetricsComponent;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsChartComponent;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsChartRefreshComponent;
use Fidum\VaporMetricsTile\Components\VaporEnvironmentMetricsComponent;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Spatie\Dashboard\Facades\Dashboard;

class VaporMetricsTileServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('vapor-environment-metrics-tile', VaporEnvironmentMetricsComponent::class);
        Livewire::component('vapor-environment-metrics-chart-tile', VaporEnvironmentMetricsChartComponent::class);
        Livewire::component('vapor-environment-metrics-chart-refresh', VaporEnvironmentMetricsChartRefreshComponent::class);
        Livewire::component('vapor-cache-metrics-tile', VaporCacheMetricsComponent::class);
        Livewire::component('vapor-database-metrics-tile', VaporDatabaseMetricsComponent::class);

        Dashboard::script('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment-with-locales.min.js');
        Dashboard::script('https://cdn.jsdelivr.net/npm/chart.js@2.8.0');

        if ($this->app->runningInConsole()) {
            $this->commands([
                FetchVaporEnvironmentMetricsCommand::class,
                FetchVaporCacheMetricsCommand::class,
                FetchVaporDatabaseMetricsCommand::class,
            ]);
        }

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/dashboard-vapor-metrics-tiles'),
        ], 'dashboard-vapor-metrics-tiles');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dashboard-vapor-metrics-tiles');
    }
}
