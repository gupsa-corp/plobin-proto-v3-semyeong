<div class="function-dependencies-container h-full">
    {{-- Header Controls --}}
    @include('700-page-sandbox.710-page-function-dependencies.components.header-controls')

    {{-- Main Content --}}
    <div class="flex h-full" style="height: calc(100% - 80px);">
        {{-- Function List Sidebar --}}
        <div class="w-80 bg-white border-r border-gray-200 overflow-y-auto">
            @include('700-page-sandbox.710-page-function-dependencies.components.function-list')
        </div>

        {{-- Main Visualization Area --}}
        <div class="flex-1 bg-gray-50 relative">
            @include('700-page-sandbox.710-page-function-dependencies.components.graph-container')
        </div>

        {{-- Function Details Sidebar --}}
        <div class="w-96 bg-white border-l border-gray-200 overflow-y-auto">
            @include('700-page-sandbox.710-page-function-dependencies.components.function-details')
        </div>
    </div>
</div>

@script
<script>
    // Livewire 이벤트 리스너들
    $wire.on('functionSelected', (functionName) => {
        if (window.dependencyGraph) {
            window.dependencyGraph.selectFunction(functionName);
        }
    });

    $wire.on('viewModeChanged', (mode) => {
        if (window.dependencyGraph) {
            window.dependencyGraph.setViewMode(mode);
        }
    });

    $wire.on('filterChanged', (filters) => {
        if (window.dependencyGraph) {
            window.dependencyGraph.applyFilters(filters);
        }
    });

    // 그래프 초기화
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof initDependencyGraph === 'function') {
            const graphData = @json($dependencyGraph);
            window.dependencyGraph = initDependencyGraph('#dependency-graph', graphData, $wire);
        }
    });
</script>
@endscript