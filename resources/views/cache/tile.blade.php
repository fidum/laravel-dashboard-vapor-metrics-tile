@php
    $nodeCount = count($data['totalCacheHits'] ?? [[]]);
    $displayNodeClass = $nodeCount > 1 ? 'pl-2' : null;
@endphp
<x-dashboard-tile
    :position="$position"
    refresh-interval="{{$refreshIntervalInSeconds}}"
>
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <h1 class="text-xl leading-none -mt-1">{{$tileName}}</h1>
        </div>
        <ul class="self-center">
            @foreach(range(0, $nodeCount - 1) as $node)
                <li class="py-1 truncate">
                    @if($displayNodeClass)
                        <h3 class="text-md">Node {{ $node + 1 }}</h3>
                    @endif
                    <ul class="self-center text-sm {{$displayNodeClass}}">
                        <li>
                            {{ number_format($data['averageCacheCpuUtilization'][$node] ?? 0) }}% <span class="text-dimmed text-xs">Average CPU Utilization</span>
                        </li>
                        <li>
                            {{ number_format($data['totalCacheHits'][$node] ?? 0) }} <span class="text-dimmed text-xs">Cache Hits</span>
                        </li>
                        <li>
                            {{ number_format($data['totalCacheMisses'][$node] ?? 0) }} <span class="text-dimmed text-xs">Cache Misses</span>
                        </li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</x-dashboard-tile>
