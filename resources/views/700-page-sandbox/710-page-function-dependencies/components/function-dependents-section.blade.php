<div>
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-lg font-medium text-gray-900">이 함수에 의존하는 함수</h4>
        <span class="text-sm text-gray-500 bg-green-100 px-2 py-1 rounded">
            {{ count($selectedFunctionData['dependents']) }}개
        </span>
    </div>
    
    @if(!empty($selectedFunctionData['dependents']))
        <div class="space-y-2">
            @foreach($selectedFunctionData['dependents'] as $dependent)
                <div class="flex items-center p-3 bg-green-50 rounded-lg border border-green-200 hover:bg-green-100 transition-colors cursor-pointer"
                     wire:click="selectFunction('{{ $dependent }}')">
                    <div class="flex items-center flex-1">
                        <svg class="w-4 h-4 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        <span class="font-medium text-green-900">{{ $dependent }}</span>
                    </div>
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            @endforeach
        </div>
        
        @if(count($selectedFunctionData['dependents']) > 0)
            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-amber-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.98-.833-2.75 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div class="text-sm text-amber-800">
                        <p class="font-medium mb-1">주의사항</p>
                        <p>이 함수를 수정하거나 삭제하면 {{ count($selectedFunctionData['dependents']) }}개의 다른 함수에 영향을 줄 수 있습니다.</p>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-6 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
            <svg class="w-8 h-8 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
            </svg>
            <p class="text-sm">이 함수에 의존하는 함수가 없습니다</p>
            <p class="text-xs text-gray-400 mt-1">안전하게 수정하거나 삭제할 수 있습니다</p>
        </div>
    @endif
</div>