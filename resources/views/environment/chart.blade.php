@livewire('chart-tile', [
    'chartClass' => \Fidum\VaporMetricsTile\Charts\BarChart::class,
    'chartFilters' => compact('tileName', 'type'),
    'height' => $height,
    'position' => $position,
    'refreshIntervalInSeconds' => $refreshIntervalInSeconds
])
