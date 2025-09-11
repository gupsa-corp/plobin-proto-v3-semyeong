<div class="space-y-6">
    <!-- 헤더 통계 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-sm">📊</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">전체 프로젝트</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $stats['total_projects'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 text-sm">✅</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">순조진행</p>
                    <p class="text-lg font-semibold text-green-600">{{ $stats['on_track'] }}</p>
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
                    <p class="text-lg font-semibold text-red-600">{{ $stats['delayed'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 text-sm">🎯</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">완료</p>
                    <p class="text-lg font-semibold text-purple-600">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 컨트롤 헤더 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex items-center space-x-4">
                <h3 class="text-lg font-medium text-gray-900">간트 차트</h3>
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
                    <button wire:click="setViewMode('quarter')"
                            class="px-3 py-1 text-xs rounded {{ $viewMode === 'quarter' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
                        분기
                    </button>
                    <button wire:click="setViewMode('year')"
                            class="px-3 py-1 text-xs rounded {{ $viewMode === 'year' ? 'bg-blue-600 text-white' : 'text-gray-600' }}">
                        년
                    </button>
                </div>
                <button wire:click="refreshData" 
                        class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    🔄 새로고침
                </button>
            </div>
        </div>
    </div>

    <!-- 간트 차트 -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <!-- 헤더 (날짜) -->
            <div class="flex border-b border-gray-200 bg-gray-50">
                <div class="w-64 p-3 text-sm font-medium text-gray-900 border-r border-gray-200">프로젝트</div>
                <div class="flex-1 flex">
                    @foreach($monthDays as $day)
                        <div class="flex-1 min-w-8 p-1 text-center border-r border-gray-200">
                            <div class="text-xs text-gray-500">{{ $day->format('j') }}</div>
                            <div class="text-xs text-gray-400">{{ $day->format('D') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 프로젝트 행들 -->
            @foreach($projects as $project)
                @php
                    $startDate = \Carbon\Carbon::parse($project['start_date']);
                    $endDate = \Carbon\Carbon::parse($project['end_date']);
                    $currentMonthStart = \Carbon\Carbon::create($currentYear, $currentMonth, 1);
                    $currentMonthEnd = $currentMonthStart->copy()->endOfMonth();
                    
                    // 현재 월에 해당하는 부분만 계산
                    $displayStart = $startDate->gte($currentMonthStart) ? $startDate : $currentMonthStart;
                    $displayEnd = $endDate->lte($currentMonthEnd) ? $endDate : $currentMonthEnd;
                    
                    $startDay = max(1, $displayStart->day);
                    $endDay = min($currentMonthEnd->day, $displayEnd->day);
                    $duration = $endDay - $startDay + 1;
                    
                    $isVisible = $startDate->lte($currentMonthEnd) && $endDate->gte($currentMonthStart);
                @endphp
                
                <div class="flex border-b border-gray-100 hover:bg-gray-50">
                    <!-- 프로젝트 정보 -->
                    <div class="w-64 p-3 border-r border-gray-200">
                        <div class="text-sm font-medium text-gray-900">{{ $project['name'] }}</div>
                        <div class="text-xs text-gray-500 mt-1">
                            <div>담당: {{ $project['created_by_name'] }}</div>
                            <div>진행: {{ $project['progress'] }}%</div>
                        </div>
                        <div class="flex items-center mt-1">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($project['status'] === 'active') bg-green-100 text-green-800
                                @elseif($project['status'] === 'in_progress') bg-blue-100 text-blue-800
                                @elseif($project['status'] === 'completed') bg-purple-100 text-purple-800
                                @elseif($project['status'] === 'blocked') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $project['status'] }}
                            </span>
                        </div>
                    </div>

                    <!-- 간트 차트 바 -->
                    <div class="flex-1 relative flex items-center" style="height: 80px;">
                        @if($isVisible)
                            @php
                                $leftOffset = (($startDay - 1) / count($monthDays)) * 100;
                                $width = ($duration / count($monthDays)) * 100;
                            @endphp
                            
                            <div class="absolute inset-y-0 flex items-center" 
                                 style="left: {{ $leftOffset }}%; width: {{ $width }}%;">
                                <!-- 진행률 바 -->
                                <div class="w-full h-6 bg-gray-200 rounded-lg overflow-hidden">
                                    <div class="h-full 
                                        @if($project['status'] === 'completed') bg-purple-500
                                        @elseif($project['status'] === 'blocked') bg-red-500
                                        @elseif($project['status'] === 'active') bg-green-500
                                        @else bg-blue-500
                                        @endif"
                                        style="width: {{ $project['progress'] }}%;">
                                    </div>
                                </div>
                                
                                <!-- 진행률 텍스트 -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-xs text-white font-medium">{{ $project['progress'] }}%</span>
                                </div>
                            </div>
                        @endif

                        <!-- 날짜 구분선들 -->
                        @foreach($monthDays as $index => $day)
                            <div class="absolute inset-y-0 border-r border-gray-100" 
                                 style="left: {{ (($index + 1) / count($monthDays)) * 100 }}%;"></div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 범례 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <h4 class="text-sm font-medium text-gray-900 mb-3">범례</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-green-500 rounded"></div>
                <span class="text-xs text-gray-600">활성 프로젝트</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                <span class="text-xs text-gray-600">진행 중</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-purple-500 rounded"></div>
                <span class="text-xs text-gray-600">완료</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-500 rounded"></div>
                <span class="text-xs text-gray-600">지연/블록</span>
            </div>
        </div>
    </div>
</div>