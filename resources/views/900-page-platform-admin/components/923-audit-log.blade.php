{{-- 플랫폼 관리자 - 감사 로그 컴포넌트 --}}
<div class="space-y-6">
    {{-- 헤더 섹션 --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">권한 감사 로그</h2>
            <p class="text-sm text-gray-600 mt-1">시스템의 모든 권한 관련 활동을 추적합니다.</p>
        </div>
        <button wire:click="exportToCSV" 
                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 transition-colors text-sm">
            📥 CSV 내보내기
        </button>
    </div>

    {{-- 필터 섹션 --}}
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">필터</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- 날짜 필터 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">기간</label>
                <select wire:model.live="dateFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">전체 기간</option>
                    <option value="today">오늘</option>
                    <option value="week">최근 7일</option>
                    <option value="month">최근 30일</option>
                    <option value="year">최근 1년</option>
                </select>
            </div>

            {{-- 사용자 필터 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">사용자</label>
                <input type="text" 
                       wire:model.live.debounce.500ms="userFilter"
                       placeholder="이메일 또는 이름 검색" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            {{-- 액션 필터 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">액션</label>
                <input type="text" 
                       wire:model.live.debounce.500ms="actionFilter"
                       placeholder="액션 검색" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            {{-- 로그 타입 필터 --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">로그 타입</label>
                <select wire:model.live="logNameFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm">
                    <option value="">전체 타입</option>
                    @foreach($logNames as $logName)
                        <option value="{{ $logName }}">{{ $logName }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- 필터 초기화 --}}
        @if($dateFilter || $userFilter || $actionFilter || $logNameFilter)
            <div class="mt-4">
                <button wire:click="clearFilters" 
                        class="text-sm text-gray-500 hover:text-gray-700 underline">
                    모든 필터 초기화
                </button>
            </div>
        @endif
    </div>

    {{-- 활동 로그 목록 --}}
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">활동 로그</h3>
                <div class="text-sm text-gray-500">
                    총 {{ $activities->total() }}개 기록
                </div>
            </div>
        </div>

        @if($activities->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($activities as $activity)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-start space-x-4">
                            {{-- 액션 타입 아이콘 --}}
                            <div class="flex-shrink-0">
                                @php
                                    $type = $this->getActivityTypeLabel($activity->description);
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $type['color'] }}-100 text-{{ $type['color'] }}-800">
                                    {{ $type['label'] }}
                                </span>
                            </div>

                            {{-- 활동 정보 --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $activity->description }}
                                    </h4>
                                    <time class="text-xs text-gray-500">
                                        {{ $activity->created_at->format('Y-m-d H:i:s') }}
                                    </time>
                                </div>

                                {{-- 사용자 정보 --}}
                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span>
                                            @if($activity->causer)
                                                {{ $activity->causer->name ?? $activity->causer->email }}
                                            @else
                                                시스템
                                            @endif
                                        </span>
                                    </div>

                                    @if($activity->subject_type)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span>
                                                {{ class_basename($activity->subject_type) }}
                                                @if($activity->subject_id)
                                                    #{{ $activity->subject_id }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif

                                    @if($activity->log_name)
                                        <div class="text-xs bg-gray-100 px-2 py-1 rounded">
                                            {{ $activity->log_name }}
                                        </div>
                                    @endif
                                </div>

                                {{-- 변경 사항 --}}
                                @if($activity->properties && count($activity->properties) > 0)
                                    @php
                                        $properties = $this->getFormattedProperties($activity->properties);
                                    @endphp
                                    @if($properties)
                                        <details class="mt-2">
                                            <summary class="cursor-pointer text-xs text-blue-600 hover:text-blue-800">
                                                변경사항 보기
                                            </summary>
                                            <div class="mt-2 p-3 bg-gray-50 rounded text-xs">
                                                @foreach($properties as $key => $value)
                                                    <div class="mb-2">
                                                        <span class="font-medium text-gray-700">{{ $key }}:</span>
                                                        <div class="mt-1 ml-2">
                                                            @if(is_array($value))
                                                                @foreach($value as $k => $v)
                                                                    <div class="mb-1">
                                                                        <span class="text-gray-600">{{ $k }}:</span>
                                                                        <span class="text-gray-900">
                                                                            {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            @else
                                                                <span class="text-gray-900">{{ $value }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- 페이지네이션 --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">활동 로그가 없습니다</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if($dateFilter || $userFilter || $actionFilter || $logNameFilter)
                            선택한 필터 조건에 맞는 활동이 없습니다.
                        @else
                            아직 기록된 활동이 없습니다.
                        @endif
                    </p>
                    @if($dateFilter || $userFilter || $actionFilter || $logNameFilter)
                        <button wire:click="clearFilters" 
                                class="mt-3 text-sm text-blue-600 hover:text-blue-800 underline">
                            필터 초기화
                        </button>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

{{-- CSV 다운로드 JavaScript --}}
<script>
document.addEventListener('livewire:initialized', function () {
    Livewire.on('download-csv', function (data) {
        const csvContent = data.data.map(row => 
            row.map(field => `"${String(field).replace(/"/g, '""')}"`)
            .join(',')
        ).join('\n');
        
        const blob = new Blob(['\ufeff' + csvContent], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        
        link.setAttribute('href', url);
        link.setAttribute('download', data.filename);
        link.style.visibility = 'hidden';
        
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });
});
</script>