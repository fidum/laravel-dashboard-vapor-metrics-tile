@php
/** @var \Fidum\VaporMetricsTile\Charts\BarChart $chart */
$containerId = "container-{$wireId}";
@endphp
<x-dashboard-tile :position="$position">
    <div class="grid grid-rows-auto-1 gap-3" id="{{$containerId}}">
        {!! $chart->container() !!}
    </div>
    {!! $chart->script() !!}
    @livewire('vapor-environment-metrics-chart-refresh', compact('tileName', 'height', 'type', 'wireId'))
</x-dashboard-tile>
