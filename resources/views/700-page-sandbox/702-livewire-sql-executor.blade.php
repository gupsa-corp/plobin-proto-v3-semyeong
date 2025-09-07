<div class="space-y-6">
    <!-- SQL 실행기 섹션 -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-medium text-gray-900">SQL 실행기</h3>
            <div class="flex items-center space-x-4">
                <div class="text-sm text-gray-500">
                    선택된 샌드박스: <span class="font-medium">storage-sandbox-{{ $selectedSandbox }}</span>
                </div>
                <button 
                    wire:click="toggleHistory"
                    class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors"
                >
                    {{ $showHistory ? '실행기로 돌아가기' : '실행 기록 보기' }}
                </button>
            </div>
        </div>

        @if(!$showHistory)
            <!-- SQL 입력 및 실행 -->
            <div class="space-y-4">
                <!-- 예제 쿼리 버튼들 -->
                <div class="flex flex-wrap gap-2">
                    <span class="text-sm text-gray-600">예제 쿼리:</span>
                    <button wire:click="loadExample('select')" 
                            class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200">
                        SELECT 조회
                    </button>
                    <button wire:click="loadExample('insert')" 
                            class="px-2 py-1 text-xs bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                        INSERT 추가
                    </button>
                    <button wire:click="loadExample('update')" 
                            class="px-2 py-1 text-xs bg-orange-100 text-orange-700 rounded hover:bg-orange-200">
                        UPDATE 수정
                    </button>
                    <button wire:click="loadExample('create')" 
                            class="px-2 py-1 text-xs bg-purple-100 text-purple-700 rounded hover:bg-purple-200">
                        CREATE TABLE
                    </button>
                    <button wire:click="loadExample('drop')" 
                            class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded hover:bg-red-200">
                        DROP TABLE
                    </button>
                </div>

                <!-- SQL 입력 창 -->
                <div>
                    <textarea 
                        wire:model="sqlQuery" 
                        placeholder="SQL 쿼리를 입력하세요... (예: SELECT * FROM users LIMIT 10;)"
                        rows="6"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono text-sm"
                        @keydown.ctrl.enter="$wire.executeSql()"
                    ></textarea>
                    <div class="text-xs text-gray-500 mt-1">
                        Ctrl + Enter를 눌러 실행하거나 아래 실행 버튼을 클릭하세요
                    </div>
                </div>

                <!-- 실행 버튼들 -->
                <div class="flex space-x-3">
                    <button 
                        wire:click="executeSql" 
                        wire:loading.attr="disabled"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 transition-colors flex items-center"
                    >
                        <span wire:loading.remove>실행</span>
                        <span wire:loading>
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            실행 중...
                        </span>
                    </button>
                    <button 
                        wire:click="clearQuery"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
                    >
                        초기화
                    </button>
                </div>

                <!-- 실행 결과 -->
                @if($executionResult)
                    <div class="mt-6">
                        <h4 class="text-md font-medium text-gray-900 mb-3">실행 결과</h4>
                        
                        @if($executionResult['status'] === 'success')
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-green-800 font-medium">
                                        성공 ({{ $executionResult['execution_time'] }}ms, {{ $executionResult['affected_rows'] }}행)
                                    </span>
                                </div>

                                @if(is_array($executionResult['data']) && isset($executionResult['data'][0]) && is_array($executionResult['data'][0]))
                                    <!-- 테이블 형식 결과 -->
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    @foreach(array_keys($executionResult['data'][0]) as $column)
                                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">
                                                            {{ $column }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                @foreach($executionResult['data'] as $row)
                                                    <tr class="hover:bg-gray-50">
                                                        @foreach($row as $value)
                                                            <td class="px-4 py-2 text-sm text-gray-900">
                                                                {{ is_null($value) ? 'NULL' : $value }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <!-- 메시지 형식 결과 -->
                                    <div class="text-green-800">
                                        @if(is_array($executionResult['data']))
                                            {{ $executionResult['data']['message'] ?? 'SQL 실행 완료' }}
                                        @else
                                            {{ $executionResult['data'] ?? 'SQL 실행 완료' }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center mb-2">
                                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-red-800 font-medium">
                                        실행 오류 ({{ $executionResult['execution_time'] }}ms)
                                    </span>
                                </div>
                                <div class="text-red-800 font-mono text-sm">
                                    {{ $executionResult['error'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <!-- 실행 기록 섹션 -->
            @if($executionHistory && $executionHistory->count() > 0)
                <div class="space-y-4">
                    @foreach($executionHistory as $execution)
                        <div class="border rounded-lg p-4 {{ $execution->status === 'success' ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-3">
                                    <span class="px-2 py-1 text-xs rounded {{ 
                                        $execution->query_type === 'SELECT' ? 'bg-green-100 text-green-800' :
                                        ($execution->query_type === 'INSERT' ? 'bg-blue-100 text-blue-800' :
                                        ($execution->query_type === 'UPDATE' ? 'bg-orange-100 text-orange-800' :
                                        ($execution->query_type === 'DELETE' ? 'bg-red-100 text-red-800' :
                                        ($execution->query_type === 'CREATE' ? 'bg-purple-100 text-purple-800' :
                                        ($execution->query_type === 'DROP' ? 'bg-gray-100 text-gray-800' : 'bg-gray-100 text-gray-800')))))
                                    }}">
                                        {{ $execution->query_type }}
                                    </span>
                                    <span class="text-sm {{ $execution->status === 'success' ? 'text-green-700' : 'text-red-700' }}">
                                        {{ $execution->status === 'success' ? '성공' : '실패' }}
                                    </span>
                                    @if($execution->execution_time_ms)
                                        <span class="text-xs text-gray-500">{{ $execution->execution_time_ms }}ms</span>
                                    @endif
                                    @if($execution->affected_rows !== null)
                                        <span class="text-xs text-gray-500">{{ $execution->affected_rows }}행</span>
                                    @endif
                                </div>
                                <span class="text-xs text-gray-500">{{ $execution->created_at->format('Y-m-d H:i:s') }}</span>
                            </div>
                            
                            <div class="bg-gray-800 text-gray-100 p-3 rounded font-mono text-sm mb-2">
                                {{ $execution->sql_query }}
                            </div>

                            @if($execution->status === 'error' && $execution->error_message)
                                <div class="text-red-700 text-sm">
                                    오류: {{ $execution->error_message }}
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- 페이지네이션 -->
                    <div class="mt-4">
                        {{ $executionHistory->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p>아직 실행 기록이 없습니다.</p>
                    <p class="text-sm text-gray-400 mt-1">SQL을 실행하면 여기에 기록이 표시됩니다.</p>
                </div>
            @endif
        @endif
    </div>

    @if(session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif
</div>