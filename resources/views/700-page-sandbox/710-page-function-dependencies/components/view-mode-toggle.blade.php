<div class="flex bg-gray-100 rounded-lg p-1">
    <button 
        wire:click="setViewMode('graph')"
        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $viewMode === 'graph' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
    >
        <span class="mr-2">📊</span>
        그래프 뷰
    </button>
    <button 
        wire:click="setViewMode('list')"
        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{ $viewMode === 'list' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
    >
        <span class="mr-2">📋</span>
        목록 뷰
    </button>
</div>