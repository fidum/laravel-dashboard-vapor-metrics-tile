<x-dashboard-tile
    :position="$position"
    refresh-interval="{{$refreshIntervalInSeconds}}"
>
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <h1 class="text-xl leading-none -mt-1">{{$tileName}}</h1>
        </div>
        <ul class="self-center divide-y-2">
            <li>
                {{ number_format($data['totalRestApiRequests'] ?? 0) }} <span class="text-dimmed text-xs">API Gateway Requests</span>
            </li>
            <li>
                {{ number_format($data['totalFunctionInvocations'] ?? 0) }} <span class="text-dimmed text-xs">Web Invocations</span>
            </li>
            <li>
                {{ number_format($data['averageFunctionDuration'] ?? 0) }}ms <span class="text-dimmed text-xs">Average Web Duration</span>
            </li>
            <li>
                {{ number_format($data['totalCliFunctionInvocations'] ?? 0) }} <span class="text-dimmed text-xs">CLI Invocations</span>
            </li>
            <li>
                {{ number_format($data['averageCliFunctionDuration'] ?? 0) }}ms <span class="text-dimmed text-xs">Average CLI Duration</span>
            </li>
            <li>
                {{ number_format($data['totalQueueFunctionInvocations'] ?? 0) }} <span class="text-dimmed text-xs">Queue Invocations</span>
            </li>
            <li>
                {{ number_format($data['averageQueueFunctionDuration'] ?? 0) }}ms <span class="text-dimmed text-xs">Average Queue Duration</span>
            </li>
        </ul>
    </div>
</x-dashboard-tile>
