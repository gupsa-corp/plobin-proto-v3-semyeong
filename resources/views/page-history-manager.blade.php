<div>
    <!-- 필터 옵션 -->
    <div class="mb-6 bg-gray-50 rounded-lg p-4">
        <div class="flex flex-wrap gap-4 items-center">
            <!-- 이벤트 타입 필터 -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">이벤트 타입:</label>
                <select wire:model="filterType" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">모든 변경</option>
                    <option value="deployment">배포 상태</option>
                    <option value="content">내용 변경</option>
                    <option value="permissions">권한 변경</option>
                </select>
            </div>
            
            <!-- 날짜 범위 필터 -->
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">기간:</label>
                <select wire:model="dateRange" class="text-sm border border-gray-300 rounded-md px-3 py-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">전체 기간</option>
                    <option value="today">오늘</option>
                    <option value="week">이번 주</option>
                    <option value="month">이번 달</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 변경 이력 목록 -->
    <div class="space-y-4">
        @forelse($logs as $log)
        <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-sm transition-shadow">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <!-- 아이콘 -->
                    <div class="flex-shrink-0">
                        @php
                            $iconColor = match($log->change_type ?? 'deployment') {
                                'permission' => 'text-purple-600 bg-purple-100',
                                'content' => 'text-green-600 bg-green-100', 
                                'name' => 'text-orange-600 bg-orange-100',
                                default => 'text-blue-600 bg-blue-100'
                            };
                        @endphp
                        <div class="w-8 h-8 {{ $iconColor }} rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! ($this->changeTypeIcon)($log->change_type ?? 'deployment') !!}
                            </svg>
                        </div>
                    </div>
                    
                    <!-- 내용 -->
                    <div class="flex-1">
                        <div class="flex items-center space-x-2">
                            <h4 class="text-sm font-medium text-gray-900">{{ ($this->changeTypeLabel)($log->change_type ?? 'deployment') }}</h4>
                            <span class="text-xs text-gray-500">•</span>
                            <span class="text-xs text-gray-500">{{ $log->created_at->format('Y-m-d H:i:s') }}</span>
                        </div>
                        
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ ($this->statusColor)($log->from_status) }}-100 text-{{ ($this->statusColor)($log->from_status) }}-800">
                                {{ ($this->statusLabel)($log->from_status) }}
                            </span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                            </svg>
                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ ($this->statusColor)($log->to_status) }}-100 text-{{ ($this->statusColor)($log->to_status) }}-800">
                                {{ ($this->statusLabel)($log->to_status) }}
                            </span>
                        </div>
                        
                        @if($log->reason)
                        <div class="mt-2">
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">변경 사유:</span> {{ $log->reason }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- 사용자 정보 -->
                <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-xs font-medium text-gray-700">{{ substr($log->user->name ?? '?', 0, 1) }}</span>
                    </div>
                    <span class="text-sm text-gray-500">{{ $log->user->name ?? '알 수 없음' }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">변경 이력이 없습니다</h3>
            <p class="mt-1 text-sm text-gray-500">아직 이 페이지에 대한 변경 기록이 없습니다.</p>
        </div>
        @endforelse
    </div>

    <!-- 페이지네이션 -->
    @if($logs->hasPages())
    <div class="mt-6">
        {{ $logs->links() }}
    </div>
    @endif
    
    <!-- 통계 정보 -->
    <div class="mt-8 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-700 mb-3">페이지 정보</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <span class="text-gray-500">생성일:</span>
                <div class="font-medium">{{ $currentPage->created_at->format('Y-m-d') }}</div>
            </div>
            <div>
                <span class="text-gray-500">마지막 수정:</span>
                <div class="font-medium">{{ $currentPage->updated_at->format('Y-m-d H:i') }}</div>
            </div>
            <div>
                <span class="text-gray-500">현재 상태:</span>
                <div class="font-medium">
                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-{{ ($this->statusColor)($currentPage->status) }}-100 text-{{ ($this->statusColor)($currentPage->status) }}-800">
                        {{ ($this->statusLabel)($currentPage->status) }}
                    </span>
                </div>
            </div>
            <div>
                <span class="text-gray-500">총 변경 횟수:</span>
                <div class="font-medium">{{ $logs->total() }}회</div>
            </div>
        </div>
    </div>
</div>