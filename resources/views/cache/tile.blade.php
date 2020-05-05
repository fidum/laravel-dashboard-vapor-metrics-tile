<x-dashboard-tile
    :position="$position"
    :refresh-interval="$refreshIntervalInSeconds"
>
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <h1 class="text-xl leading-none -mt-1">{{$tileName}}</h1>
        </div>
        <ul class="self-center divide-y-2">
            @foreach(range(0, count($data['totalCacheHits'] ?? [[]]) - 1) as $node)
                <li class="py-1 truncate">
                    <h3 class="text-md">Node {{ $node + 1 }}</h3>
                    <ul class="self-center space-y-1 text-sm pl-2">
                        <li> {{ number_format($data['averageCacheCpuUtilization'][$node] ?? 0).'%' }} <span class="text-dimmed">Average CPU Utilization</span></li>
                        <li> {{ number_format($data['totalCacheHits'][$node] ?? 0) }} <span class="text-dimmed">Cache Hits</span></li>
                        <li> {{ number_format($data['totalCacheMisses'][$node] ?? 0) }} <span class="text-dimmed">Cache Misses</span></li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
