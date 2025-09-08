<div class="bg-white">
    <!-- 필터 영역 -->
    <div class="p-6 bg-gray-50 border-b border-gray-200">
        <h2 class="text-lg font-medium text-gray-900 mb-4">프로젝트 변경 로그</h2>
        
        <!-- 필터 폼 -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- 액션 타입 필터 -->
            <div>
                <label for="filterAction" class="block text-sm font-medium text-gray-700 mb-1">액션 타입</label>
                <select wire:model.live="filterAction" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">전체</option>
                    @foreach($actionTypes as $action => $label)
                        <option value="{{ $action }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- 사용자 필터 -->
            <div>
                <label for="filterUser" class="block text-sm font-medium text-gray-700 mb-1">사용자</label>
                <input type="text" wire:model.live.debounce.300ms="filterUser" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                       placeholder="이름 또는 이메일">
            </div>

            <!-- 시작 날짜 -->
            <div>
                <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">시작 날짜</label>
                <input type="date" wire:model.live="dateFrom"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>

            <!-- 종료 날짜 -->
            <div>
                <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">종료 날짜</label>
                <input type="date" wire:model.live="dateTo"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
        </div>

        <!-- 필터 초기화 버튼 -->
        <div class="mt-4">
            <button type="button" wire:click="clearFilters" 
                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                필터 초기화
            </button>
        </div>
    </div>

    <!-- 로그 리스트 -->
    <div class="p-6">
        @if($logs->count() > 0)
            <div class="space-y-4">
                @foreach($logs as $log)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <!-- 액션 정보 -->
                                <div class="flex items-center space-x-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        @if(str_contains($log->action, 'created')) bg-green-100 text-green-800
                                        @elseif(str_contains($log->action, 'updated')) bg-blue-100 text-blue-800
                                        @elseif(str_contains($log->action, 'deleted')) bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $log->action_description }}
                                    </span>
                                    <span class="text-sm text-gray-500">{{ $log->relative_time }}</span>
                                </div>
                                
                                <!-- 사용자 정보 -->
                                <div class="mt-2 flex items-center space-x-2">
                                    <div class="h-6 w-6 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-gray-600">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $log->user->name }}</span>
                                    <span class="text-sm text-gray-500">{{ $log->user->email }}</span>
                                </div>

                                <!-- 설명 -->
                                @if($log->description)
                                    <div class="mt-2 text-sm text-gray-600">
                                        {{ $log->description }}
                                    </div>
                                @endif

                                <!-- 메타데이터 -->
                                @if($log->metadata && count($log->metadata) > 0)
                                    <div class="mt-2">
                                        <details class="group">
                                            <summary class="cursor-pointer text-sm text-gray-500 hover:text-gray-700 group-open:text-gray-700">
                                                세부 정보 보기
                                            </summary>
                                            <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                                <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </details>
                                    </div>
                                @endif
                            </div>

                            <!-- 시간 정보 -->
                            <div class="text-right text-sm text-gray-500">
                                <div>{{ $log->created_at->format('Y-m-d') }}</div>
                                <div>{{ $log->created_at->format('H:i:s') }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- 페이지네이션 -->
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        @else
            <!-- 빈 상태 -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">변경 로그가 없습니다</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($filterAction || $filterUser || $dateFrom || $dateTo)
                        선택한 필터 조건에 해당하는 로그가 없습니다.
                    @else
                        아직 프로젝트에 변경 이력이 없습니다.
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- 로딩 상태 -->
    <div wire:loading class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 shadow-lg">
            <div class="flex items-center space-x-3">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
                <span class="text-sm text-gray-900">로그를 불러오는 중...</span>
            </div>
        </div>
    </div>
</div>
