<div>
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-lg font-medium text-gray-900">의존하는 함수</h4>
        <span class="text-sm text-gray-500 bg-blue-100 px-2 py-1 rounded">
            {{ count($selectedFunctionData['dependencies']) }}개
        </span>
    </div>
    
    @if(!empty($selectedFunctionData['dependencies']))
        <div class="space-y-2">
            @foreach($selectedFunctionData['dependencies'] as $dependency)
                <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors cursor-pointer"
                     wire:click="selectFunction('{{ $dependency }}')">
                    <div class="flex items-center flex-1">
                        <svg class="w-4 h-4 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        <span class="font-medium text-blue-900">{{ $dependency }}</span>
                    </div>
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <p class="text-sm">의존하는 함수가 없습니다</p>
            <p class="text-xs text-gray-400 mt-1">독립적으로 실행 가능한 함수입니다</p>
        </div>
    @endif
</div>