<div 
    class="p-3 rounded-lg border cursor-pointer transition-all duration-200 hover:bg-blue-50 hover:border-blue-200 hover:shadow-sm {{ $selectedFunction === $functionName ? 'bg-blue-50 border-blue-300 shadow-sm' : 'bg-white border-gray-200' }}"
    wire:click="selectFunction('{{ $functionName }}')"
>
    <div class="flex items-start justify-between">
        <div class="flex-1">
            {{-- Function Name --}}
            <h4 class="font-medium text-gray-900 mb-1">{{ $functionName }}</h4>
            
            {{-- Description --}}
            <p class="text-sm text-gray-600 mb-2 line-clamp-2">
                {{ Str::limit($functionData['description'] ?? '설명이 없습니다.', 60) }}
            </p>
            
            {{-- Category Badge --}}
            @if(!empty($functionData['category']))
                @include('700-page-sandbox.710-page-function-dependencies.components.category-badge', [
                    'category' => $functionData['category']
                ])
            @endif

            {{-- Stats --}}
            @include('700-page-sandbox.710-page-function-dependencies.components.function-stats', [
                'dependencies' => count($functionData['dependencies'] ?? []),
                'versions' => count($functionData['versions'] ?? [])
            ])
        </div>
        
        {{-- Selection Indicator --}}
        @if($selectedFunction === $functionName)
            <div class="text-blue-500 ml-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
        @endif
    </div>
</div>