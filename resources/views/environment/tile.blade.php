<x-dashboard-tile
    :position="$position"
    :refresh-interval="$refreshIntervalInSeconds"
>
    <div class="grid grid-rows-auto-1 gap-2 h-full">
        <div class="flex items-center justify-center">
            <h1 class="text-3xl leading-none -mt-1">{{$tileName}}</h1>
            <h3 class="text-2xl leading-none -mt-1">Vapor environment metrics ({{$period}})</h3>
        </div>
        <ul class="self-center">
            <li> {{ number_format($data['totalRestApiRequests']) }} <span class="text-dimmed">API Gateway Requests</span></li>
            <li> {{ number_format($data['totalFunctionInvocations']) }} <span class="text-dimmed">Web Invocations</span></li>
            <li> {{ number_format($data['averageFunctionDuration']) }}ms <span class="text-dimmed">Average Web Duration</span></li>
            <li> {{ number_format($data['totalCliFunctionInvocations']) }} <span class="text-dimmed">CLI Invocations</span></li>
            <li> {{ number_format($data['averageCliFunctionDuration']) }}ms <span class="text-dimmed">Average CLI Duration</span></li>
            <li> {{ number_format($data['totalQueueFunctionInvocations']) }} <span class="text-dimmed">Queue Invocations</span></li>
            <li> {{ number_format($data['averageQueueFunctionDuration']) }}ms <span class="text-dimmed">Average Queue Duration</span></li>
        </ul>
    </div>
</x-dashboard-tile>
