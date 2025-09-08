{{-- 파라미터 입력 --}}
<div class="p-3 border-b border-gray-200">
    <label class="block text-sm font-medium text-gray-700 mb-2">파라미터 (JSON)</label>
    <textarea
        x-model="testParams"
        rows="4"
        class="w-full p-2 border border-gray-300 rounded text-xs font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        placeholder='{"action": "get_projects", "data": {}}'
    ></textarea>
    <button
        @click="$wire.testFunction(testParams)"
        class="mt-2 w-full px-3 py-2 bg-green-500 text-white text-sm rounded hover:bg-green-600"
    >
        🧪 함수 실행
    </button>
</div>

{{-- 테스트 결과 --}}
<div class="flex-1 overflow-auto p-3">
    <h4 class="text-sm font-medium text-gray-700 mb-3">실행 결과</h4>
    
    @forelse($testResults as $result)
        <div class="mb-3 p-3 border rounded-lg {{ $result['success'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium {{ $result['success'] ? 'text-green-700' : 'text-red-700' }}">
                    {{ $result['timestamp'] }} - {{ $result['function'] }}({{ $result['version'] }})
                </span>
                <span class="text-xs px-2 py-1 rounded {{ $result['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $result['success'] ? '성공' : '실패' }}
                </span>
            </div>
            
            @if($result['success'])
                <div class="text-xs">
                    <div class="mb-1 text-gray-600">결과:</div>
                    <pre class="text-xs bg-white p-2 rounded border overflow-auto">{{ json_encode($result['result'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </div>
            @else
                <div class="text-xs text-red-600">
                    <div class="mb-1">오류:</div>
                    <div class="bg-white p-2 rounded border">{{ $result['error'] }}</div>
                </div>
            @endif
        </div>
    @empty
        <div class="text-center py-8 text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <p class="text-sm">테스트 결과가 여기에 표시됩니다</p>
        </div>
    @endforelse
</div>

{{-- Global Functions 섹션 --}}
<div class="border-t border-gray-200 p-3">
    @include('livewire.sandbox.partials.global-functions-panel')
</div>