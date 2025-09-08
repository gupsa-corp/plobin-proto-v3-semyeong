<h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center justify-center">
    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    </svg>
    Global Functions
</h4>

{{-- 함수 선택 --}}
<select wire:model="selectedGlobalFunction" class="w-full mb-2 px-2 py-1 text-xs border border-gray-300 rounded focus:ring-2 focus:ring-purple-500 focus:border-transparent">
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
        <div class="mb-2 p-2 bg-purple-50 border border-purple-200 rounded text-xs">
            <div class="font-medium text-purple-800 mb-1">필수 파라미터:</div>
            @foreach($selectedFunc['parameters'] as $param => $info)
                @if($info['required'])
                    <div class="text-purple-700">
                        <strong>{{ $param }}</strong> ({{ $info['type'] }}): {{ $info['description'] }}
                    </div>
                @endif
            @endforeach
        </div>
    @endif
@endif

{{-- 파라미터 입력 --}}
<textarea 
    wire:model="globalFunctionParams" 
    placeholder='{"data": [["Name","Age"],["John",25]], "filename": "test.xlsx"}'
    class="w-full p-2 text-xs border border-gray-300 rounded mb-2 font-mono focus:ring-2 focus:ring-purple-500 focus:border-transparent"
    rows="4">
</textarea>

{{-- 실행 버튼 --}}
<button 
    wire:click="executeGlobalFunction"
    class="w-full bg-purple-500 text-white text-sm rounded py-2 hover:bg-purple-600 transition-colors disabled:bg-purple-300"
    {{ empty($selectedGlobalFunction) ? 'disabled' : '' }}
>
    🔧 Global Function 실행
</button>

{{-- 결과 표시 --}}
<div class="mt-3 text-xs">
    <div class="mb-2 font-medium text-gray-700">실행 결과:</div>
    @forelse(array_reverse($globalFunctionResults) as $result)
        <div class="p-2 border rounded mb-2 {{ $result['success'] ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
            <div class="flex items-center justify-between mb-1">
                <div class="font-medium text-xs {{ $result['success'] ? 'text-green-700' : 'text-red-700' }}">
                    {{ $result['timestamp'] }} - {{ $result['function'] }}
                </div>
                <span class="text-xs px-1 py-0.5 rounded {{ $result['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $result['success'] ? '성공' : '실패' }}
                </span>
            </div>
            <div class="text-gray-600 text-xs">{{ $result['message'] }}</div>
            @if(isset($result['file_path']))
                <div class="mt-1">
                    <a href="{{ $result['file_path'] }}" class="text-blue-600 underline text-xs hover:text-blue-800" target="_blank">
                        📥 파일 다운로드
                    </a>
                </div>
            @endif
            @if(isset($result['data']))
                <div class="mt-1 text-xs text-gray-500">
                    처리된 행: {{ $result['data']['rows_processed'] ?? 'N/A' }}, 
                    열: {{ $result['data']['columns_processed'] ?? 'N/A' }}
                </div>
            @endif
        </div>
    @empty
        <div class="text-gray-500 text-center py-4">
            실행 결과가 여기에 표시됩니다
        </div>
    @endforelse
</div>