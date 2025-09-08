<div class="absolute top-4 right-4 bg-white rounded-lg shadow-sm border border-gray-200 p-2">
    <div class="flex flex-col space-y-2">
        {{-- Reset View --}}
        <button 
            id="reset-graph" 
            class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors"
            title="그래프 초기화"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </button>

        {{-- Fit to Screen --}}
        <button 
            id="fit-graph" 
            class="p-2 text-gray-600 hover:text-green-600 hover:bg-green-50 rounded transition-colors"
            title="화면에 맞추기"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
            </svg>
        </button>

        {{-- Zoom In --}}
        <button 
            id="zoom-in" 
            class="p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded transition-colors"
            title="확대"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
        </button>

        {{-- Zoom Out --}}
        <button 
            id="zoom-out" 
            class="p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded transition-colors"
            title="축소"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"></path>
            </svg>
        </button>
    </div>
</div>