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
        window.dependencyGraph?.selectFunction(functionName);
    });

    $wire.on('viewModeChanged', (mode) => {
        window.dependencyGraph?.setViewMode(mode);
    });

    $wire.on('filterChanged', (filters) => {
        window.dependencyGraph?.applyFilters(filters);
    });

    // 그래프 초기화 - 최적화된 버전
    document.addEventListener('DOMContentLoaded', function() {
        const loadGraph = () => {
            if (typeof initDependencyGraph !== 'function') {
                console.error('initDependencyGraph function not found');
                document.getElementById('graph-loading').innerHTML = '<div class="text-center text-red-500">그래프 초기화 함수를 찾을 수 없습니다.</div>';
                return;
            }
            
            const graphData = @json($dependencyGraph);
            console.log('Graph data received:', graphData);
            
            if (!graphData || !graphData.nodes || graphData.nodes.length === 0) {
                console.warn('Empty graph data received');
            }
            
            window.dependencyGraph = initDependencyGraph('#dependency-graph', graphData, $wire);
        };

        // D3.js 로딩 확인
        if (typeof d3 !== 'undefined') {
            loadGraph();
        } else {
            console.warn('D3.js not loaded, waiting...');
            setTimeout(() => {
                if (typeof d3 !== 'undefined') {
                    loadGraph();
                } else {
                    document.getElementById('graph-loading').innerHTML = '<div class="text-center text-red-500">D3.js 라이브러리를 로드할 수 없습니다.</div>';
                }
            }, 1000);
        }
    });
</script>
@endscript