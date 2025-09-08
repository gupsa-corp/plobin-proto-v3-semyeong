<div class="relative w-full h-full">
    {{-- Graph Loading State --}}
    <div id="graph-loading" class="absolute inset-0 flex items-center justify-center bg-gray-50">
        <div class="text-center">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">의존성 그래프 로딩 중...</p>
        </div>
    </div>

    {{-- Main Graph Container --}}
    <div id="dependency-graph" class="w-full h-full"></div>

    {{-- Graph Controls --}}
    @include('700-page-sandbox.710-page-function-dependencies.components.graph-controls')

    {{-- Graph Legend --}}
    @include('700-page-sandbox.710-page-function-dependencies.components.graph-legend')
</div>