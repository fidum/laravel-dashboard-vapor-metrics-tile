@php
/** @var \Fidum\VaporMetricsTile\Charts\BarChart $chart */
@endphp
<x-dashboard-tile :position="$position" refresh_interval="{{$refreshIntervalInSeconds}}">
    <div class="grid grid-rows-auto-1 gap-3">
        {!! $chart->container() !!}
    </div>
    {!! $chart->script() !!}
    @push('scripts')
        <script>
            window.livewire.on('polledEvent{{$wireId}}', function () {
                {{ $chart->id }}_rendered = false;
                {{ $chart->id }}_load();
            });
        </script>
    @endpush
</x-dashboard-tile>
