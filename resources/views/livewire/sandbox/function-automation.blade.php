<div class="p-6 overflow-auto" style="height: calc(100vh - 140px);">
    {{-- Function Details Modal --}}
    @if($showFunctionDetails)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeFunctionDetails">
        <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-96 overflow-y-auto" wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">{{ $selectedFunctionInfo['name'] ?? 'Function Details' }}</h3>
                <button wire:click="closeFunctionDetails" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">설명</label>
                    <p class="mt-1 text-sm text-gray-600">{{ $selectedFunctionInfo['description'] ?? '설명 없음' }}</p>
                </div>
                
                @if(isset($selectedFunctionInfo['parameters']) && !empty($selectedFunctionInfo['parameters']))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">매개변수</label>
                    <div class="bg-gray-50 rounded p-3">
                        @foreach($selectedFunctionInfo['parameters'] as $paramName => $paramInfo)
                        <div class="mb-2 last:mb-0">
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $paramName }}</span>
                                <span class="text-xs text-gray-500">{{ $paramInfo['type'] ?? 'mixed' }}</span>
                                @if($paramInfo['required'] ?? false)
                                    <span class="text-xs bg-red-100 text-red-800 px-1 rounded">필수</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600 mt-1">{{ $paramInfo['description'] ?? '' }}</p>
                            @if(isset($paramInfo['example']))
                            <p class="text-xs text-gray-500 mt-1">예시: 
                                <code class="bg-gray-100 px-1 rounded">{{ is_string($paramInfo['example']) ? $paramInfo['example'] : json_encode($paramInfo['example']) }}</code>
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $selectedFunctionInfo['type'] === 'global' ? 'green' : 'blue' }}-100 text-{{ $selectedFunctionInfo['type'] === 'global' ? 'green' : 'blue' }}-800">
                        {{ $selectedFunctionInfo['type'] === 'global' ? '🌐 전역 함수' : '📦 저장된 함수' }}
                    </span>
                    <button wire:click="testSingleFunction('{{ $selectedFunctionInfo['name'] }}')" 
                            class="px-3 py-1 bg-blue-500 text-white text-xs rounded hover:bg-blue-600">
                        테스트 실행
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Save Workflow Modal --}}
    @if($showSaveModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" wire:click="closeSaveModal">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4" wire:click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">워크플로우 저장</h3>
                <button wire:click="closeSaveModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">워크플로우 이름</label>
                    <input type="text" wire:model.defer="workflowName" 
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                           placeholder="MyWorkflow">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">설명 (선택사항)</label>
                    <textarea wire:model.defer="workflowDescription" 
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                              rows="3"
                              placeholder="워크플로우에 대한 설명을 입력하세요..."></textarea>
                </div>
            </div>
            
            <div class="flex justify-end space-x-3 mt-6">
                <button wire:click="closeSaveModal" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                    취소
                </button>
                <button wire:click="saveWorkflow" 
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                    저장
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-12 gap-6 h-full">
        {{-- Left Panel: Functions and Templates --}}
        <div class="col-span-3 space-y-4 h-full overflow-y-auto">
            {{-- Global Functions --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">🌐</span>
                        전역 함수 ({{ count($globalFunctions) }})
                    </h3>
                </div>
                <div class="p-4 max-h-48 overflow-y-auto">
                    @forelse($globalFunctions as $func)
                    <div class="mb-2 last:mb-0">
                        <div class="flex items-center justify-between p-2 border border-green-200 rounded hover:bg-green-50 cursor-pointer">
                            <div class="flex-1" wire:click="insertFunction('{{ $func['name'] }}')">
                                <div class="font-medium text-sm text-green-800">{{ $func['name'] }}</div>
                                <div class="text-xs text-green-600">{{ Str::limit($func['description'], 30) }}</div>
                            </div>
                            <button wire:click="showFunctionInfo('{{ $func['name'] }}')" 
                                    class="ml-2 text-green-600 hover:text-green-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">전역 함수가 없습니다</p>
                    @endforelse
                </div>
            </div>

            {{-- Storage Functions --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">📦</span>
                        저장된 함수 ({{ count($storageFunctions) }})
                    </h3>
                </div>
                <div class="p-4 max-h-48 overflow-y-auto">
                    @forelse($storageFunctions as $func)
                    <div class="mb-2 last:mb-0">
                        <div class="flex items-center justify-between p-2 border border-blue-200 rounded hover:bg-blue-50 cursor-pointer">
                            <div class="flex-1" wire:click="insertFunction('{{ $func['name'] }}')">
                                <div class="font-medium text-sm text-blue-800">{{ $func['name'] }}</div>
                                <div class="text-xs text-blue-600">{{ Str::limit($func['description'], 30) }}</div>
                            </div>
                            <button wire:click="showFunctionInfo('{{ $func['name'] }}')" 
                                    class="ml-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">저장된 함수가 없습니다</p>
                    @endforelse
                </div>
            </div>

            {{-- Workflow Templates --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">📝</span>
                        템플릿 ({{ count($workflowTemplates) }})
                    </h3>
                </div>
                <div class="p-4 max-h-48 overflow-y-auto">
                    @forelse($workflowTemplates as $template)
                    <div class="mb-2 last:mb-0">
                        <div class="p-2 border border-gray-200 rounded hover:bg-gray-50 cursor-pointer" 
                             wire:click="selectTemplate('{{ $template['name'] }}')">
                            <div class="font-medium text-sm text-gray-800">{{ $template['name'] }}</div>
                            <div class="text-xs text-gray-600">{{ Str::limit($template['description'], 40) }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">템플릿이 없습니다</p>
                    @endforelse
                </div>
            </div>

            {{-- Saved Workflows --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">💾</span>
                        저장된 워크플로우 ({{ count($savedWorkflows) }})
                    </h3>
                </div>
                <div class="p-4 max-h-48 overflow-y-auto">
                    @forelse($savedWorkflows as $workflow)
                    <div class="mb-2 last:mb-0">
                        <div class="p-2 border border-purple-200 rounded hover:bg-purple-50 cursor-pointer" 
                             wire:click="loadSavedWorkflow('{{ $workflow['name'] }}')">
                            <div class="font-medium text-sm text-purple-800">{{ $workflow['name'] }}</div>
                            <div class="text-xs text-purple-600">{{ Str::limit($workflow['description'], 40) }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm text-center py-4">저장된 워크플로우가 없습니다</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Center Panel: Code Editor --}}
        <div class="col-span-6">
            <div class="bg-white rounded-lg shadow h-full flex flex-col">
                <div class="p-4 border-b border-gray-200 flex justify-between items-center">
                    <div class="flex items-center">
                        <h3 class="font-medium text-gray-900 flex items-center">
                            <span class="mr-2">⚡</span>
                            워크플로우 코드 에디터
                        </h3>
                        @if($selectedTemplate)
                        <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            템플릿: {{ $selectedTemplate }}
                        </span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <button wire:click="executeWorkflow" 
                                wire:loading.attr="disabled"
                                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed flex items-center">
                            <span wire:loading.remove>실행</span>
                            <span wire:loading class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                실행중...
                            </span>
                        </button>
                        <button wire:click="openSaveModal" 
                                class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            저장
                        </button>
                        <button wire:click="resetWorkflow" 
                                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                            초기화
                        </button>
                    </div>
                </div>
                <div class="flex-1 p-0">
                    <textarea wire:model.defer="workflowCode" 
                             class="w-full h-full font-mono text-sm border-0 rounded-b-lg p-4 resize-none focus:ring-0"
                             placeholder="PHP 워크플로우 코드를 입력하세요..."></textarea>
                </div>
            </div>
        </div>

        {{-- Right Panel: Execution and Results --}}
        <div class="col-span-3 space-y-4 h-full overflow-y-auto">
            {{-- Test Input --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">🧪</span>
                        테스트 입력
                    </h3>
                </div>
                <div class="p-4">
                    <textarea wire:model.defer="testInput" 
                             class="w-full h-32 text-sm border border-gray-300 rounded p-2 font-mono focus:ring-blue-500 focus:border-blue-500"
                             placeholder='{"data": "test value"}'></textarea>
                </div>
            </div>

            {{-- Execution Results --}}
            <div class="bg-white rounded-lg shadow flex-1">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">📊</span>
                        실행 결과
                        @if(!empty($executionResult))
                            @if($executionResult['success'] ?? false)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    성공
                                </span>
                            @else
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    실패
                                </span>
                            @endif
                        @endif
                    </h3>
                </div>
                <div class="p-4">
                    <div class="bg-gray-50 rounded p-3 h-64 overflow-auto">
                        @if(!empty($executionResult))
                            <pre class="text-xs whitespace-pre-wrap">{{ json_encode($executionResult, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                        @else
                            <div class="text-gray-500 text-sm">워크플로우를 실행하면 결과가 여기 표시됩니다.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Execution Log --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900 flex items-center">
                        <span class="mr-2">📋</span>
                        실행 로그
                        @if(!empty($executionLog))
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                            {{ count($executionLog) }}건
                        </span>
                        @endif
                    </h3>
                </div>
                <div class="p-4">
                    <div class="bg-gray-50 rounded p-3 h-32 overflow-auto">
                        @if(!empty($executionLog))
                            @foreach($executionLog as $log)
                            <div class="text-xs mb-1 last:mb-0">
                                <span class="text-gray-500">{{ $log['timestamp'] }}</span>
                                <span class="text-gray-700">{{ $log['message'] }}</span>
                            </div>
                            @endforeach
                        @else
                            <div class="text-gray-500 text-sm">실행 로그가 여기 표시됩니다.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="bg-white rounded-lg shadow">
                <div class="p-4 border-b border-gray-200">
                    <h3 class="font-medium text-gray-900">빠른 작업</h3>
                </div>
                <div class="p-4 space-y-2">
                    <button wire:click="refreshFunctions" 
                            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded">
                        🔄 함수 목록 새로고침
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>