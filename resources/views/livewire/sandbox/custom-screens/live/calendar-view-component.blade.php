<div class="space-y-6">
    <!-- 헤더 통계 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-sm">📅</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">전체 일정</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $stats['total_events'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 text-sm">📍</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">이번 달</p>
                    <p class="text-lg font-semibold text-green-600">{{ $stats['this_month_events'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-orange-600 text-sm">⏰</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">예정</p>
                    <p class="text-lg font-semibold text-orange-600">{{ $stats['upcoming_events'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="text-red-600 text-sm">⚠️</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">지연</p>
                    <p class="text-lg font-semibold text-red-600">{{ $stats['overdue_events'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 컨트롤 헤더 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-medium text-gray-900">프로젝트 달력</h3>
                <div class="flex items-center space-x-2">
                    <button wire:click="previousMonth" 
                            class="px-2 py-1 text-sm bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                        ←
                    </button>
                    <span class="text-sm font-medium text-gray-900">
                        {{ $currentYear }}년 {{ $currentMonth }}월
                    </span>
                    <button wire:click="nextMonth"
                            class="px-2 py-1 text-sm bg-gray-100 text-gray-600 rounded hover:bg-gray-200">
                        →
                    </button>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button wire:click="setViewMode('month')"
                            class="px-3 py-1 text-xs rounded {{ $viewMode === 'month' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
                        월
                    </button>
                    <button wire:click="setViewMode('week')"
                            class="px-3 py-1 text-xs rounded {{ $viewMode === 'week' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
                        주
                    </button>
                    <button wire:click="setViewMode('day')"
                            class="px-3 py-1 text-xs rounded {{ $viewMode === 'day' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
                        일
                    </button>
                </div>
                <button wire:click="refreshData" 
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    🔄 새로고침
                </button>
            </div>
        </div>
    </div>

    <!-- 달력 -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <!-- 요일 헤더 -->
        <div class="grid grid-cols-7 bg-gray-50 border-b border-gray-200">
            @foreach(['월', '화', '수', '목', '금', '토', '일'] as $day)
                <div class="p-3 text-center text-sm font-medium text-gray-900">{{ $day }}</div>
            @endforeach
        </div>

        <!-- 달력 날짜들 -->
        <div class="grid grid-cols-7">
            @foreach($calendarDays as $day)
                <div wire:click="selectDate('{{ $day['date']->format('Y-m-d') }}')"
                     class="min-h-24 p-2 border-r border-b border-gray-100 cursor-pointer hover:bg-gray-50 
                            {{ !$day['isCurrentMonth'] ? 'bg-gray-50 text-gray-400' : '' }}
                            {{ $day['isToday'] ? 'bg-blue-50' : '' }}">
                    
                    <!-- 날짜 -->
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-sm {{ $day['isToday'] ? 'font-bold text-blue-600' : '' }}">
                            {{ $day['date']->format('j') }}
                        </span>
                        @if($day['isToday'])
                            <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                        @endif
                    </div>

                    <!-- 이벤트들 -->
                    <div class="space-y-1">
                        @foreach($day['events'] as $index => $event)
                            @if($index < 3) <!-- 최대 3개 이벤트만 표시 -->
                                <div class="text-xs p-1 rounded truncate
                                    @if($event['type'] === 'start') bg-green-100 text-green-800
                                    @elseif($event['type'] === 'end') bg-red-100 text-red-800
                                    @elseif($event['type'] === 'milestone') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($event['type'] === 'start') 🚀
                                    @elseif($event['type'] === 'end') 🎯
                                    @elseif($event['type'] === 'milestone') 📍
                                    @endif
                                    {{ $event['title'] }}
                                </div>
                            @endif
                        @endforeach
                        
                        @if(count($day['events']) > 3)
                            <div class="text-xs text-gray-500 text-center">
                                +{{ count($day['events']) - 3 }}개 더
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 선택된 날짜 상세 -->
    @if($selectedDate)
        @php
            $selectedEvents = array_filter($events, function($event) {
                return $event['date'] === $selectedDate;
            });
        @endphp
        
        @if(!empty($selectedEvents))
            <div class="bg-white rounded-lg border border-gray-200 p-4">
                <h4 class="text-lg font-medium text-gray-900 mb-3">
                    {{ \Carbon\Carbon::parse($selectedDate)->format('Y년 m월 d일') }} 일정
                </h4>
                <div class="space-y-3">
                    @foreach($selectedEvents as $event)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <div class="flex items-center justify-between mb-2">
                                <h5 class="text-sm font-medium text-gray-900">{{ $event['title'] }}</h5>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($event['type'] === 'start') bg-green-100 text-green-800
                                    @elseif($event['type'] === 'end') bg-red-100 text-red-800
                                    @elseif($event['type'] === 'milestone') bg-blue-100 text-blue-800
                                    @endif">
                                    @if($event['type'] === 'start') 프로젝트 시작
                                    @elseif($event['type'] === 'end') 완료 예정
                                    @elseif($event['type'] === 'milestone') 마일스톤
                                    @endif
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 mb-1">{{ $event['description'] }}</p>
                            <p class="text-xs text-gray-500">조직: {{ $event['organization'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif

    <!-- 범례 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <h4 class="text-sm font-medium text-gray-900 mb-3">이벤트 유형</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-100 border border-green-200 rounded"></div>
                <span class="text-xs text-gray-600">🚀 프로젝트 시작</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-blue-100 border border-blue-200 rounded"></div>
                <span class="text-xs text-gray-600">📍 마일스톤</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-100 border border-red-200 rounded"></div>
                <span class="text-xs text-gray-600">🎯 완료 예정</span>
            </div>
        </div>
    </div>
</div>