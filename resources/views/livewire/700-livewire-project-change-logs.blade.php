<div>
    <!-- 변경 로그 목록 -->
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">프로젝트 변경 로그</h3>
            
            <!-- 필터 옵션 -->
            <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <!-- 액션 필터 -->
                <div>
                    <label for="filterAction" class="block text-sm font-medium text-gray-700 mb-1">액션</label>
                    <select wire:model="filterAction" id="filterAction" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">모든 액션</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}">{{ $this->getActionName($action) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- 사용자 필터 -->
                <div>
                    <label for="filterUser" class="block text-sm font-medium text-gray-700 mb-1">사용자</label>
                    <select wire:model="filterUser" id="filterUser" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <option value="">모든 사용자</option>
                        @foreach($users as $user)
                            @if($user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- 시작 날짜 -->
                <div>
                    <label for="dateFrom" class="block text-sm font-medium text-gray-700 mb-1">시작 날짜</label>
                    <input type="date" wire:model="dateFrom" id="dateFrom" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>

                <!-- 종료 날짜 -->
                <div>
                    <label for="dateTo" class="block text-sm font-medium text-gray-700 mb-1">종료 날짜</label>
                    <input type="date" wire:model="dateTo" id="dateTo" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                </div>
            </div>

            <!-- 필터 리셋 버튼 -->
            <div class="mb-4">
                <button wire:click="clearFilters" 
                        class="px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    필터 초기화
                </button>
            </div>

            @if($logs->count() > 0)
                <!-- 로그 테이블 -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    시간
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    액션
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    사용자
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    설명
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    상세 정보
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($logs as $log)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $log->created_at->format('Y-m-d H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                            @if(str_contains($log->action, 'created')) bg-green-100 text-green-800
                                            @elseif(str_contains($log->action, 'updated')) bg-blue-100 text-blue-800
                                            @elseif(str_contains($log->action, 'deleted')) bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ $this->getActionName($log->action) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $log->user ? $log->user->name : '시스템' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $log->description ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        @if($log->metadata)
                                            <button x-data="{ open: false }" 
                                                    @click="open = !open"
                                                    class="text-blue-600 hover:text-blue-800 underline">
                                                상세 보기
                                            </button>
                                            <div x-show="open" 
                                                 x-collapse
                                                 class="mt-2 p-2 bg-gray-50 rounded text-xs">
                                                <pre>{{ json_encode($log->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- 페이지네이션 -->
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">로그가 없습니다</h3>
                    <p class="mt-1 text-sm text-gray-500">아직 기록된 변경 로그가 없습니다.</p>
                </div>
            @endif
        </div>
    </div>
</div>