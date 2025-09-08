@if(!empty($selectedFunctionData['info']['tags']))
    <div>
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-lg font-medium text-gray-900">태그</h4>
            <span class="text-sm text-gray-500 bg-indigo-100 px-2 py-1 rounded">
                {{ count($selectedFunctionData['info']['tags']) }}개
            </span>
        </div>
        
        <div class="flex flex-wrap gap-2">
            @foreach($selectedFunctionData['info']['tags'] as $tag)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 border border-indigo-200">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    {{ $tag }}
                </span>
            @endforeach
        </div>
    </div>
@endif