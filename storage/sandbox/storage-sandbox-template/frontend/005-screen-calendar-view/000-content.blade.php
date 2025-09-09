<div class="bg-gray-100 p-6 rounded-lg min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title ?? '프로젝트 캘린더' }}</h2>
            <p class="text-gray-600">{{ $description ?? '일정과 마일스톤을 캘린더로 관리하세요' }}</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                새 일정
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors" onclick="changeView('month')">
                월간
            </button>
            <button class="px-4 py-2 bg-yellow-600 text-white rounded-md hover:bg-yellow-700 transition-colors" onclick="changeView('week')">
                주간
            </button>
            <button class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors" onclick="changeView('day')">
                일간
            </button>
        </div>
    </div>

    <?php
    // 캘린더 데이터 생성 함수
    $generateCalendarData = function() {
        $currentDate = date('Y-m-01'); // 현재 월의 1일
        $today = date('Y-m-d');
        
        // 이벤트 데이터 생성
        $events = [
            [
                'id' => 'EVT-001',
                'title' => '프로젝트 킥오프 미팅',
                'date' => date('Y-m-d', strtotime($today . ' -3 days')),
                'time' => '10:00',
                'duration' => '2시간',
                'type' => 'meeting',
                'priority' => 'high',
                'attendees' => ['김PM', '이개발', '박디자인'],
                'description' => '프로젝트 전체 개요 및 역할 분담 논의'
            ],
            [
                'id' => 'EVT-002',
                'title' => 'UI/UX 리뷰',
                'date' => date('Y-m-d', strtotime($today . ' -1 days')),
                'time' => '14:00',
                'duration' => '1.5시간',
                'type' => 'review',
                'priority' => 'medium',
                'attendees' => ['박디자인', '최기획'],
                'description' => '와이어프레임 및 프로토타입 검토'
            ],
            [
                'id' => 'EVT-003',
                'title' => '스프린트 플래닝',
                'date' => $today,
                'time' => '09:00',
                'duration' => '3시간',
                'type' => 'planning',
                'priority' => 'high',
                'attendees' => ['김PM', '이개발', '최테스터'],
                'description' => '2주 스프린트 백로그 및 태스크 계획'
            ],
            [
                'id' => 'EVT-004',
                'title' => '데일리 스탠드업',
                'date' => date('Y-m-d', strtotime($today . ' +1 days')),
                'time' => '09:30',
                'duration' => '30분',
                'type' => 'standup',
                'priority' => 'low',
                'attendees' => ['전체팀'],
                'description' => '일일 진행 상황 공유'
            ],
            [
                'id' => 'EVT-005',
                'title' => 'API 개발 완료',
                'date' => date('Y-m-d', strtotime($today . ' +3 days')),
                'time' => null,
                'duration' => null,
                'type' => 'milestone',
                'priority' => 'urgent',
                'attendees' => ['이백엔드'],
                'description' => '백엔드 API 개발 마일스톤'
            ],
            [
                'id' => 'EVT-006',
                'title' => '코드 리뷰',
                'date' => date('Y-m-d', strtotime($today . ' +5 days')),
                'time' => '15:00',
                'duration' => '2시간',
                'type' => 'review',
                'priority' => 'medium',
                'attendees' => ['이개발', '정시니어'],
                'description' => '주간 코드 품질 검토 및 피드백'
            ],
            [
                'id' => 'EVT-007',
                'title' => '스프린트 회고',
                'date' => date('Y-m-d', strtotime($today . ' +7 days')),
                'time' => '16:00',
                'duration' => '1시간',
                'type' => 'retrospective',
                'priority' => 'medium',
                'attendees' => ['전체팀'],
                'description' => '스프린트 성과 분석 및 개선점 도출'
            ],
            [
                'id' => 'EVT-008',
                'title' => '고객 데모',
                'date' => date('Y-m-d', strtotime($today . ' +10 days')),
                'time' => '11:00',
                'duration' => '1시간',
                'type' => 'demo',
                'priority' => 'urgent',
                'attendees' => ['김PM', '고객사'],
                'description' => '개발 진행 상황 데모 및 피드백 수집'
            ],
            [
                'id' => 'EVT-009',
                'title' => 'QA 테스트 시작',
                'date' => date('Y-m-d', strtotime($today . ' +12 days')),
                'time' => null,
                'duration' => null,
                'type' => 'milestone',
                'priority' => 'high',
                'attendees' => ['최테스터'],
                'description' => '통합 테스트 및 품질 보증 시작'
            ],
            [
                'id' => 'EVT-010',
                'title' => '배포 준비',
                'date' => date('Y-m-d', strtotime($today . ' +14 days')),
                'time' => '10:00',
                'duration' => '4시간',
                'type' => 'deployment',
                'priority' => 'urgent',
                'attendees' => ['윤데브옵스', '이백엔드'],
                'description' => '프로덕션 배포 준비 및 인프라 점검'
            ]
        ];
        
        return [$currentDate, $today, $events];
    };
    
    $getEventTypeInfo = function($type) {
        $typeMap = [
            'meeting' => ['color' => 'bg-blue-500', 'icon' => '👥', 'label' => '회의'],
            'review' => ['color' => 'bg-purple-500', 'icon' => '👁️', 'label' => '검토'],
            'planning' => ['color' => 'bg-green-500', 'icon' => '📋', 'label' => '계획'],
            'standup' => ['color' => 'bg-yellow-500', 'icon' => '🏃', 'label' => '스탠드업'],
            'milestone' => ['color' => 'bg-red-500', 'icon' => '🏁', 'label' => '마일스톤'],
            'retrospective' => ['color' => 'bg-indigo-500', 'icon' => '🔄', 'label' => '회고'],
            'demo' => ['color' => 'bg-pink-500', 'icon' => '🎯', 'label' => '데모'],
            'deployment' => ['color' => 'bg-gray-500', 'icon' => '🚀', 'label' => '배포']
        ];
        return $typeMap[$type] ?? ['color' => 'bg-gray-500', 'icon' => '📅', 'label' => '기타'];
    };
    
    $getPriorityColor = function($priority) {
        $priorityMap = [
            'urgent' => 'border-l-4 border-red-600',
            'high' => 'border-l-4 border-orange-500',
            'medium' => 'border-l-4 border-blue-500',
            'low' => 'border-l-4 border-gray-400'
        ];
        return $priorityMap[$priority] ?? 'border-l-4 border-gray-400';
    };

    list($currentDate, $today, $events) = $generateCalendarData();
    
    // 월간 캘린더를 위한 날짜 계산
    $firstDay = strtotime($currentDate);
    $monthName = date('Y년 m월', $firstDay);
    $firstDayOfWeek = date('w', $firstDay); // 0=일요일, 6=토요일
    $daysInMonth = date('t', $firstDay);
    
    // 캘린더 시작일 (이전 월의 마지막 날들 포함)
    $calendarStart = strtotime('-' . $firstDayOfWeek . ' days', $firstDay);
    $calendarDays = [];
    
    // 6주치 날짜 생성 (42일)
    for ($i = 0; $i < 42; $i++) {
        $currentCalendarDate = date('Y-m-d', strtotime('+' . $i . ' days', $calendarStart));
        $dayNumber = date('j', strtotime($currentCalendarDate));
        $isCurrentMonth = date('m', strtotime($currentCalendarDate)) === date('m', $firstDay);
        $isToday = $currentCalendarDate === $today;
        
        // 해당 날짜의 이벤트들 필터링
        $dayEvents = array_filter($events, function($event) use ($currentCalendarDate) {
            return $event['date'] === $currentCalendarDate;
        });
        
        $calendarDays[] = [
            'date' => $currentCalendarDate,
            'day' => $dayNumber,
            'isCurrentMonth' => $isCurrentMonth,
            'isToday' => $isToday,
            'events' => array_values($dayEvents)
        ];
    }
    ?>

    <!-- 캘린더 네비게이션 -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="previousMonth()" class="p-2 hover:bg-gray-100 rounded-md transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h3 class="text-xl font-semibold text-gray-900">{{$monthName}}</h3>
                <button onclick="nextMonth()" class="p-2 hover:bg-gray-100 rounded-md transition-colors">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <button onclick="goToToday()" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition-colors">
                오늘
            </button>
        </div>
    </div>

    <!-- 월간 캘린더 -->
    <div id="monthly-view" class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- 요일 헤더 -->
        <div class="grid grid-cols-7 bg-gray-50 border-b">
            @foreach(['일', '월', '화', '수', '목', '금', '토'] as $dayName)
            <div class="p-3 text-center text-sm font-medium text-gray-700 {{$dayName === '일' ? 'text-red-600' : ($dayName === '토' ? 'text-blue-600' : '')}}">
                {{$dayName}}
            </div>
            @endforeach
        </div>

        <!-- 캘린더 날짜 그리드 -->
        <div class="grid grid-cols-7">
            @foreach($calendarDays as $day)
            <div class="min-h-32 border-b border-r border-gray-200 p-2 relative {{$day['isCurrentMonth'] ? 'bg-white' : 'bg-gray-50'}} {{$day['isToday'] ? 'bg-blue-50' : ''}}"
                 data-date="{{$day['date']}}">
                
                <!-- 날짜 표시 -->
                <div class="flex justify-between items-start mb-2">
                    <span class="text-sm font-medium {{$day['isCurrentMonth'] ? 'text-gray-900' : 'text-gray-400'}} {{$day['isToday'] ? 'bg-blue-600 text-white rounded-full w-6 h-6 flex items-center justify-center' : ''}}">
                        {{$day['day']}}
                    </span>
                    @if(count($day['events']) > 3)
                    <span class="text-xs text-gray-500 bg-gray-100 rounded-full px-2">
                        +{{count($day['events']) - 3}}
                    </span>
                    @endif
                </div>

                <!-- 이벤트 목록 (최대 3개만 표시) -->
                <div class="space-y-1">
                    @foreach(array_slice($day['events'], 0, 3) as $event)
                    <?php $eventInfo = $getEventTypeInfo($event['type']); ?>
                    <div class="text-xs p-1 rounded cursor-pointer hover:opacity-80 transition-opacity {{$eventInfo['color']}} text-white truncate {{$getPriorityColor($event['priority'])}}"
                         onclick="showEventDetail('{{$event['id']}}')"
                         title="{{$event['title']}} - {{$event['time'] ?? '종일'}}">
                        <span class="mr-1">{{$eventInfo['icon']}}</span>
                        {{$event['title']}}
                    </div>
                    @endforeach
                </div>

                <!-- 날짜 클릭 영역 -->
                <div class="absolute inset-0 cursor-pointer hover:bg-blue-50 hover:bg-opacity-50 transition-colors"
                     onclick="selectDate('{{$day['date']}}')"
                     title="{{$day['date']}}에 새 일정 추가">
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- 오늘의 일정 -->
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">오늘의 일정</h3>
        <div class="space-y-3">
            @foreach(array_filter($events, function($event) use ($today) { return $event['date'] === $today; }) as $todayEvent)
            <?php $eventInfo = $getEventTypeInfo($todayEvent['type']); ?>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg {{$getPriorityColor($todayEvent['priority'])}}">
                <div class="flex-shrink-0 w-10 h-10 {{$eventInfo['color']}} rounded-full flex items-center justify-center text-white">
                    {{$eventInfo['icon']}}
                </div>
                <div class="ml-3 flex-1">
                    <h4 class="font-medium text-gray-900">{{$todayEvent['title']}}</h4>
                    <div class="text-sm text-gray-600 mt-1">
                        @if($todayEvent['time'])
                        <span class="mr-4">🕐 {{$todayEvent['time']}}</span>
                        @endif
                        @if($todayEvent['duration'])
                        <span class="mr-4">⏱️ {{$todayEvent['duration']}}</span>
                        @endif
                        <span>👥 {{implode(', ', $todayEvent['attendees'])}}</span>
                    </div>
                </div>
                <button class="flex-shrink-0 px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded hover:bg-blue-200 transition-colors"
                        onclick="showEventDetail('{{$todayEvent['id']}}')">
                    상세보기
                </button>
            </div>
            @endforeach
            
            @if(empty(array_filter($events, function($event) use ($today) { return $event['date'] === $today; })))
            <div class="text-center text-gray-500 py-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p>오늘 예정된 일정이 없습니다.</p>
                <button class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                        onclick="selectDate('{{$today}}')">
                    새 일정 추가
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- 다가오는 일정 -->
    <div class="mt-6 bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">다가오는 일정</h3>
        <div class="space-y-3">
            @foreach(array_filter($events, function($event) use ($today) { return $event['date'] > $today; }) as $upcomingEvent)
            <?php $eventInfo = $getEventTypeInfo($upcomingEvent['type']); ?>
            <div class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition-colors">
                <div class="flex-shrink-0 w-8 h-8 {{$eventInfo['color']}} rounded-full flex items-center justify-center text-white text-sm">
                    {{$eventInfo['icon']}}
                </div>
                <div class="ml-3 flex-1">
                    <div class="flex items-center gap-2">
                        <h4 class="font-medium text-gray-900">{{$upcomingEvent['title']}}</h4>
                        <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">{{$eventInfo['label']}}</span>
                    </div>
                    <div class="text-sm text-gray-600 mt-1">
                        <span class="mr-4">📅 {{$upcomingEvent['date']}}</span>
                        @if($upcomingEvent['time'])
                        <span class="mr-4">🕐 {{$upcomingEvent['time']}}</span>
                        @endif
                    </div>
                </div>
                <div class="text-sm text-gray-500">
                    {{floor((strtotime($upcomingEvent['date']) - strtotime($today)) / (60 * 60 * 24))}}일 후
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<script>
let currentDate = new Date();

document.addEventListener('DOMContentLoaded', function() {
    // 이벤트 클릭 처리
    window.showEventDetail = function(eventId) {
        console.log('이벤트 상세보기:', eventId);
        // 이벤트 상세 모달을 열 수 있습니다
    };
    
    // 날짜 선택 처리
    window.selectDate = function(date) {
        console.log('날짜 선택:', date);
        // 새 일정 추가 모달을 열 수 있습니다
    };
    
    // 월 네비게이션
    window.previousMonth = function() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        console.log('이전 월:', currentDate.toISOString().slice(0, 7));
        // 캘린더 새로고침 로직
    };
    
    window.nextMonth = function() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        console.log('다음 월:', currentDate.toISOString().slice(0, 7));
        // 캘린더 새로고침 로직
    };
    
    window.goToToday = function() {
        currentDate = new Date();
        console.log('오늘로 이동:', currentDate.toISOString().slice(0, 10));
        // 캘린더 새로고침 로직
    };
    
    // 뷰 변경
    window.changeView = function(view) {
        console.log('뷰 변경:', view);
        // 월간/주간/일간 뷰 변경 로직
        if (view === 'week') {
            // 주간 뷰 표시
            document.getElementById('monthly-view').style.display = 'none';
            // 주간 뷰 컨테이너 표시 (구현 필요)
        } else if (view === 'day') {
            // 일간 뷰 표시
            document.getElementById('monthly-view').style.display = 'none';
            // 일간 뷰 컨테이너 표시 (구현 필요)
        } else {
            // 월간 뷰 표시
            document.getElementById('monthly-view').style.display = 'block';
        }
    };
    
    // 드래그 앤 드롭으로 일정 이동 (향후 구현)
    // enableEventDragAndDrop();
});
</script>