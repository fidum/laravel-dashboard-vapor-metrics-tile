@livewire('chart-tile', [
    'chartFactory' => \Fidum\VaporMetricsTile\Charts\BarChart::class,
    'chartSettings' => compact('tileName', 'type'),
    'height' => $height,
    'position' => $position,
    'refreshIntervalInSeconds' => $refreshIntervalInSeconds
])
