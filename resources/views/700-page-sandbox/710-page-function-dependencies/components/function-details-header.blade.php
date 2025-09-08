<div class="border-b border-gray-200 pb-4">
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-xl font-semibold text-gray-900">{{ $selectedFunction }}</h3>
        @if(!empty($selectedFunctionData['info']['category']))
            @include('700-page-sandbox.710-page-function-dependencies.components.category-badge', [
                'category' => $selectedFunctionData['info']['category']
            ])
        @endif
    </div>
    
    <p class="text-gray-600 mb-3 leading-relaxed">
        {{ $selectedFunctionData['info']['description'] ?? '설명이 없습니다.' }}
    </p>
    
    {{-- Basic Info Grid --}}
    <div class="grid grid-cols-2 gap-4 text-sm">
        <div class="space-y-1">
            <div class="text-gray-500">생성일</div>
            <div class="font-medium">
                {{ $selectedFunctionData['info']['created_at'] ? \Carbon\Carbon::parse($selectedFunctionData['info']['created_at'])->format('Y-m-d H:i') : 'N/A' }}
            </div>
        </div>
        <div class="space-y-1">
            <div class="text-gray-500">작성자</div>
            <div class="font-medium">{{ $selectedFunctionData['info']['author'] ?? 'N/A' }}</div>
        </div>
    </div>
</div>