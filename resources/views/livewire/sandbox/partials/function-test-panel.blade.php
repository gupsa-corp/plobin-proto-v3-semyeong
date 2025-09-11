{{-- 함수 테스트 탭 시스템 --}}
<div
    x-data="functionTestPanel()"
    class="border-b border-gray-200"
>
    {{-- 탭 헤더 --}}
    <div class="flex border-b border-gray-100 bg-gray-50">
        <button
            @click="activeTestTab = 'params'"
            :class="activeTestTab === 'params' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
            class="px-3 py-2 text-xs font-medium transition-colors"
        >
            🛠️ 파라미터
        </button>
        <button
            @click="activeTestTab = 'examples'"
            :class="activeTestTab === 'examples' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
            class="px-3 py-2 text-xs font-medium transition-colors"
        >
            📝 예시
        </button>
        <button
            @click="activeTestTab = 'history'"
            :class="activeTestTab === 'history' ? 'bg-white border-b-2 border-blue-500 text-blue-600' : 'text-gray-600 hover:text-gray-800'"
            class="px-3 py-2 text-xs font-medium transition-colors"
        >
            📋 히스토리
        </button>
    </div>

    {{-- 파라미터 입력 탭 --}}
    <div x-show="activeTestTab === 'params'" class="p-3">
        <div class="flex justify-between items-center mb-2">
            <label class="block text-sm font-medium text-gray-700">파라미터 (JSON)</label>
            <div class="flex items-center space-x-2">
                <div x-show="!jsonValid" class="text-xs text-red-600 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    JSON 형식 오류
                </div>
                <div x-show="jsonValid" class="text-xs text-green-600 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    유효한 JSON
                </div>
            </div>
        </div>

        {{-- 필수 파라미터 안내 --}}
        <div class="mb-2 p-2 bg-blue-50 border border-blue-200 rounded text-xs">
            <div class="font-medium text-blue-800 mb-1">필수 파라미터:</div>
            <div class="space-y-1">
                <div class="text-blue-700"><strong>data</strong> (array): 처리할 데이터 배열</div>
                <div class="text-blue-700"><strong>operation</strong> (string): filter | transform | aggregate | validate | process</div>
            </div>
            <div class="mt-1 text-blue-600 text-xs">💡 예시 탭에서 다양한 사용법을 확인하세요</div>
        </div>

        <div class="relative">
            <textarea
                x-model="testParams"
                @input="validateJson($event.target.value)"
                rows="6"
                class="w-full p-3 border border-gray-300 rounded-lg text-xs font-mono focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :class="!jsonValid ? 'border-red-300 bg-red-50' : 'bg-white'"
                placeholder='{"data": [{"name": "John", "age": 30}], "operation": "process"}'
                style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace; line-height: 1.4;"
            ></textarea>

            {{-- JSON 포맷 버튼 --}}
            <button
                @click="formatJson()"
                class="absolute top-2 right-2 px-2 py-1 bg-gray-100 hover:bg-gray-200 text-xs rounded border border-gray-300"
                title="JSON 포맷 정리"
            >
                🎨 정리
            </button>
        </div>

        <button
            @click="$wire.testFunction(testParams)"
            :disabled="!jsonValid"
            class="mt-3 w-full px-3 py-2 text-sm rounded transition-colors"
            :class="jsonValid ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
        >
            🧪 함수 실행
        </button>
    </div>

    {{-- 예시 탭 --}}
    <div x-show="activeTestTab === 'examples'" class="p-3">
        <div class="space-y-3">
            @if(!empty($parameterExamples))
                @foreach($parameterExamples as $index => $example)
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700">예시 {{ $index + 1 }} (로그 기반)</span>
                            <button
                                onclick="useLogExample('{{ addslashes($example) }}')"
                                class="px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded transition-colors"
                            >
                                📋 사용
                            </button>
                        </div>
                        <pre class="text-xs bg-gray-50 p-2 rounded border overflow-x-auto" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">{{ $example }}</pre>
                    </div>
                @endforeach
            @else
                {{-- 기본 예시 (로그가 없을 때) --}}
                <template x-for="(example, operation) in parameterExamples" :key="operation">
                    <div class="border border-gray-200 rounded-lg p-3">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700" x-text="operation.toUpperCase() + ' 작업'"></span>
                            <button
                                @click="testParams = example; activeTestTab = 'params'; validateJson(example);"
                                class="px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 text-xs rounded transition-colors"
                            >
                                📋 사용
                            </button>
                        </div>
                        <pre class="text-xs bg-gray-50 p-2 rounded border overflow-x-auto" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;" x-text="JSON.stringify(JSON.parse(example), null, 2)"></pre>
                    </div>
                </template>
            @endif
        </div>
    </div>

    {{-- 히스토리 탭 --}}
    <div x-show="activeTestTab === 'history'" class="p-3">
        {{-- 날짜 선택 드롭다운 --}}
        @if(!empty($availableLogDates))
            <div class="mb-4 pb-3 border-b border-gray-200">
                <label class="block text-sm font-medium text-gray-700 mb-2">로그 날짜 선택</label>
                <select 
                    wire:model.live="selectedLogDate"
                    wire:change="selectLogDate($event.target.value)"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    @foreach($availableLogDates as $date)
                        <option value="{{ $date }}">{{ $date }} ({{ \Carbon\Carbon::parse($date)->format('m월 d일') }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        {{-- 로그 히스토리 표시 --}}
        @if(!empty($logHistory))
            <div class="space-y-2 max-h-64 overflow-y-auto">
                @foreach(array_slice($logHistory, 0, 20) as $log)
                    <div class="border rounded-lg p-2 {{ $log['success'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                        <div class="flex justify-between items-start mb-1">
                            <div class="text-xs {{ $log['success'] ? 'text-green-700' : 'text-red-700' }} font-medium">
                                {{ $log['timestamp'] }} ({{ \Carbon\Carbon::parse($log['datetime'])->format('H:i:s') }})
                            </div>
                            <button
                                onclick="useLogExample('{{ addslashes($log['params_raw']) }}')"
                                class="px-2 py-1 bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-300 text-xs rounded transition-colors"
                                title="이 파라미터로 다시 실행"
                            >
                                🔄 재실행
                            </button>
                        </div>
                        <div class="text-xs text-gray-600 mb-1">
                            파라미터: <code class="bg-white px-1 rounded">{{ Str::limit($log['params_raw'], 50) }}</code>
                        </div>
                        <div class="text-xs {{ $log['success'] ? 'text-green-600' : 'text-red-600' }}">
                            {{ $log['success'] ? '✅ 성공' : '❌ 실패: ' . (isset($log['error']) ? $log['error'] : '알 수 없는 오류') }}
                        </div>
                    </div>
                @endforeach
            </div>
            
            @if(count($logHistory) > 20)
                <div class="mt-3 text-center">
                    <p class="text-xs text-gray-500">{{ count($logHistory) }}개 중 최근 20개를 표시했습니다.</p>
                </div>
            @endif
        @elseif(!empty($availableLogDates))
            <div class="text-center py-8 text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">선택한 날짜에 실행 히스토리가 없습니다</p>
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg class="w-8 h-8 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm">실행 히스토리가 없습니다</p>
                <p class="text-xs text-gray-400 mt-1">함수를 실행하면 로그가 저장됩니다</p>
            </div>
        @endif
    </div>
</div>

{{-- 실행 결과 --}}
<div class="flex-1 overflow-auto">
    <div class="p-3 border-b border-gray-200 bg-gray-50">
        <h4 class="text-sm font-medium text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            최근 실행 결과
            @if(count($testResults) > 0)
                <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded">{{ count($testResults) }}개</span>
            @endif
        </h4>
    </div>

    <div class="p-3">
        @forelse(array_slice($testResults, -3) as $result)
            <div class="mb-4 border rounded-lg {{ $result['success'] ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }} relative group">
                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button
                        data-params="{{ htmlspecialchars(json_encode(isset($result['params']) ? $result['params'] : []), ENT_QUOTES, 'UTF-8') }}"
                        onclick="rerunWithParams(this)"
                        class="px-2 py-1 bg-white hover:bg-blue-50 border border-gray-200 hover:border-blue-300 text-xs rounded transition-colors shadow-sm"
                        title="이 파라미터로 다시 실행"
                    >
                        🔄 재실행
                    </button>
                </div>

                <div class="p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-medium {{ $result['success'] ? 'text-green-700' : 'text-red-700' }}">
                            {{ isset($result['timestamp']) ? $result['timestamp'] : now()->format('H:i:s') }} - {{ isset($result['function']) ? $result['function'] : 'unknown' }}({{ isset($result['version']) ? $result['version'] : '1.0' }})
                        </span>
                        <span class="text-xs px-2 py-1 rounded {{ $result['success'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $result['success'] ? '✅ 성공' : '❌ 실패' }}
                        </span>
                    </div>

                    {{-- 파라미터 표시 --}}
                    <div class="mb-2">
                        <div class="text-xs text-gray-600 mb-1">입력 파라미터:</div>
                        <details class="text-xs">
                            <summary class="cursor-pointer text-blue-600 hover:text-blue-800">파라미터 보기</summary>
                            <pre class="mt-1 bg-white p-2 rounded border text-xs overflow-auto" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">{{ json_encode(isset($result['params']) ? $result['params'] : [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        </details>
                    </div>

                    @if(isset($result['success']) && $result['success'])
                        <div class="text-xs">
                            <div class="mb-1 text-gray-600">실행 결과:</div>
                            <div class="bg-white p-2 rounded border max-h-32 overflow-auto">
                                <pre class="text-xs" style="font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;">{{ json_encode(isset($result['result']) ? $result['result'] : null, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @else
                        <div class="text-xs text-red-600">
                            <div class="mb-1">오류 메시지:</div>
                            <div class="bg-white p-2 rounded border text-red-700">{{ isset($result['error']) ? $result['error'] : '알 수 없는 오류' }}</div>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <p class="text-sm">테스트 결과가 여기에 표시됩니다</p>
                <p class="text-xs text-gray-400 mt-1">파라미터를 입력하고 함수를 실행해보세요</p>
            </div>
        @endforelse

        @if(count($testResults) > 3)
            <div class="text-center mt-4">
                <button
                    onclick="switchToHistoryTab()"
                    class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded border transition-colors"
                >
                    📋 모든 히스토리 보기 ({{ count($testResults) }}개)
                </button>
            </div>
        @endif
    </div>
</div>

{{-- Global Functions 섹션 --}}
<div class="border-t border-gray-200">
    <div class="p-3 bg-gray-50 border-b border-gray-200">
        <h4 class="text-sm font-medium text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            Global Functions
        </h4>
    </div>
    <div class="p-3">
        @include('livewire.sandbox.partials.global-functions-panel')
    </div>
</div>

<script>
// Function test panel data function
function functionTestPanel() {
    return {
        activeTestTab: 'params',
        jsonValid: true,
        testParams: '{}',
        parameterExamples: {
            filter: '{"data": [{"name": "John", "age": 30}, {"name": "Jane", "age": 25}], "operation": "filter", "criteria": {"age": {">=": 25}}}',
            transform: '{"data": [{"name": "John Doe", "email": "john@example.com"}], "operation": "transform", "mapping": {"full_name": "name", "contact_email": "email"}}',
            aggregate: '{"data": [{"category": "A", "value": 100}, {"category": "A", "value": 150}, {"category": "B", "value": 200}], "operation": "aggregate", "group_by": "category", "functions": ["sum", "avg"]}',
            validate: '{"data": [{"email": "test@example.com", "age": "25"}], "operation": "validate", "rules": {"email": "email", "age": "integer"}}',
            process: '{"data": [{"id": 1, "status": "pending"}], "operation": "process"}'
        },
        
        validateJson(value) {
            if (!value) {
                this.jsonValid = true;
                return;
            }
            try {
                JSON.parse(value);
                this.jsonValid = true;
            } catch (e) {
                this.jsonValid = false;
            }
        },
        
        formatJson() {
            try {
                const parsed = JSON.parse(this.testParams || '{}');
                this.testParams = JSON.stringify(parsed, null, 2);
                this.validateJson(this.testParams);
            } catch (e) {
                console.error('JSON 파싱 오류:', e);
            }
        },
        
        init() {
            this.validateJson(this.testParams);
            this.$watch('testParams', (value) => this.validateJson(value));
        }
    };
}

// Helper functions for parameter re-execution
function rerunWithParams(button) {
    try {
        const params = JSON.parse(button.dataset.params || '{}');
        const testParamsEl = document.querySelector('[x-model="testParams"]');
        if (testParamsEl) {
            testParamsEl.value = JSON.stringify(params, null, 2);
            testParamsEl.dispatchEvent(new Event('input'));
        }
        // Switch to params tab
        const paramsTabButton = document.querySelector('[\\@click*="params"]');
        if (paramsTabButton) {
            paramsTabButton.click();
        }
    } catch (e) {
        console.error('Failed to load parameters:', e);
    }
}

// 로그 예시 사용 함수
function useLogExample(params) {
    try {
        const testParamsEl = document.querySelector('[x-model="testParams"]');
        if (testParamsEl) {
            testParamsEl.value = params;
            testParamsEl.dispatchEvent(new Event('input'));
            
            // JSON 검증 함수 호출
            if (window.Alpine && testParamsEl._x_dataStack) {
                const data = testParamsEl._x_dataStack[0];
                if (data && data.validateJson) {
                    data.validateJson(params);
                }
            }
        }
        
        // Switch to params tab
        const paramsTabButton = document.querySelector('[\\@click*="params"]');
        if (paramsTabButton) {
            paramsTabButton.click();
        }
    } catch (e) {
        console.error('Failed to use log example:', e);
    }
}

function switchToHistoryTab() {
    const historyTabButton = document.querySelector('[\\@click*="history"]');
    if (historyTabButton) {
        historyTabButton.click();
    }
}
</script>
