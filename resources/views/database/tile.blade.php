<x-dashboard-tile
    :position="$position"
    :refresh-interval="$refreshIntervalInSeconds"
>
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <h1 class="text-xl leading-none -mt-1">{{$tileName}}</h1>
        </div>
        <ul class="self-center">
            <li>
                {{ number_format($data['averageDatabaseCpuUtilization'] ?? 0) }}% <span class="text-dimmed text-xs">Average CPU Utilization</span>
            </li>
            <li>
                {{ number_format($data['averageDatabaseConnections'] ?? 0) }} <span class="text-dimmed text-xs">Average Database Connections</span>
            </li>
            <li>
                {{ number_format($data['maxDatabaseConnections'] ?? 0) }} <span class="text-dimmed text-xs">Max Database Connections</span>
            </li>
        </ul>
    </div>
</x-dashboard-tile>
