@php
/** @var \Fidum\VaporMetricsTile\Charts\BarChart $chart */
@endphp
<div class="hidden" wire:poll.{{$refreshIntervalInSeconds}}s></div>
@push('scripts')
    <script>
        window.livewire.on('polledEvent{{$wireId}}', function (newData) {
            var chartObj = window.{{$chart->id}};
            chartObj.data.labels = newData.labels;
            chartObj.data.datasets = JSON.parse(newData.datasets);
            chartObj.options = newData.options;
            chartObj.update();
        });
    </script>
@endpush
