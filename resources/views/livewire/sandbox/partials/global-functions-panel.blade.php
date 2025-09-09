<h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center justify-center">
    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    </svg>
    Global Functions
</h4>

{{-- 함수 선택 --}}
<select wire:model="selectedGlobalFunction" class="w-full mb-3 px-2 py-2 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white">
    <option value="">함수 선택...</option>
    @foreach($availableGlobalFunctions as $func)
        <option value="{{ $func['name'] }}">{{ $func['name'] }} - {{ $func['description'] }}</option>
    @endforeach
</select>

{{-- 선택된 함수의 파라미터 정보 표시 --}}
@if($selectedGlobalFunction)
    @php
        $selectedFunc = collect($availableGlobalFunctions)->firstWhere('name', $selectedGlobalFunction);
    @endphp
    @if($selectedFunc)
        <div class="mb-3 p-3 bg-purple-50 border border-purple-200 rounded-lg text-xs">
            <div class="font-medium text-purple-800 mb-2 flex items-center">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                필수 파라미터:
            </div>
            <div class="space-y-1">
                @foreach($selectedFunc['parameters'] as $param => $info)
                    @if($info['required'])
                        <div class="text-purple-700 bg-white p-2 rounded border border-purple-100">
                            <div class="flex justify-between items-start">
                                <strong class="text-purple-800">{{ $param }}</strong>
                                <span class="px-1 py-0.5 bg-purple-100 text-purple-700 rounded text-xs">{{ $info['type'] }}</span>
                            </div>
                            <div class="text-purple-600 text-xs mt-1">{{ $info['description'] }}</div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
@endif

{{-- 파라미터 입력 --}}
<div class="mb-3">
    <label class="block text-xs font-medium text-gray-700 mb-1">파라미터 (JSON)</label>
    <div class="relative">
        <textarea 
            wire:model="globalFunctionParams" 
            placeholder='{"data": [["Name","Age"],["John",25]], "filename": "test.xlsx"}'
            class="w-full p-2 text-xs border border-gray-300 rounded-lg font-mono focus:ring-2 focus:ring-purple-500 focus:border-transparent bg-white"
            style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; line-height: 1.4;"
            rows="4"
        ></textarea>
        <button
            onclick="
                try {
                    const textarea = this.previousElementSibling;
                    const parsed = JSON.parse(textarea.value || '{}');
                    textarea.value = JSON.stringify(parsed, null, 2);
                    textarea.dispatchEvent(new Event('input'));
                } catch (e) {
                    console.error('JSON 파싱 오류:', e);
                }
            "
            class="absolute top-1 right-1 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-xs rounded border border-gray-300"
            title="JSON 포맷 정리"
        >
            🎨 정리
        </button>
    </div>
</div>

{{-- 실행 버튼 --}}
<button 
    wire:click="executeGlobalFunction"
    class="w-full text-sm rounded-lg py-2 transition-colors font-medium"
    :class="{{ empty($selectedGlobalFunction) ? 'true' : 'false' }} ? 'bg-gray-300 text-gray-500 cursor-not-allowed' : 'bg-purple-500 hover:bg-purple-600 text-white'"
    {{ empty($selectedGlobalFunction) ? 'disabled' : '' }}
>
    🔧 Global Function 실행
</button>

{{-- 결과 표시 --}}
<div class="mt-4">
    <div class="mb-2 font-medium text-gray-700 text-xs flex items-center">
        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        실행 결과:
    </div>
    
    <div class="max-h-48 overflow-y-auto">
        @forelse(array_reverse($globalFunctionResults) as $result)
            <div class="p-3 border rounded-lg mb-2 {{ $result['success'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                <div class="flex items-center justify-between mb-2">
                    <div class="font-medium text-xs {{ $result['success'] ? 'text-green-700' : 'text-red-700' }}">
                        {{ $result['timestamp'] }} - {{ $result['function'] }}
                    </div>
                    <span class="text-xs px-2 py-1 rounded {{ $result['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $result['success'] ? '✅ 성공' : '❌ 실패' }}
                    </span>
                </div>
                
                <div class="text-gray-600 text-xs mb-2">{{ $result['message'] }}</div>
                
                @if(isset($result['file_path']))
                    <div class="mt-2">
                        <a href="{{ $result['file_path'] }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 text-xs underline" target="_blank">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            파일 다운로드
                        </a>
                    </div>
                @endif
                
                @if(isset($result['data']))
                    <div class="mt-2 p-2 bg-white rounded border text-xs text-gray-600">
                        <div class="font-medium mb-1">처리 정보:</div>
                        <div>• 처리된 행: {{ $result['data']['rows_processed'] ?? 'N/A' }}</div>
                        <div>• 처리된 열: {{ $result['data']['columns_processed'] ?? 'N/A' }}</div>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-gray-500 text-center py-6">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <div class="text-sm">실행 결과가 여기에 표시됩니다</div>
            </div>
        @endforelse
    </div>
</div>