<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title ?? '프로젝트 간트 차트' }}</h2>
            <p class="text-gray-600">{{ $description ?? '프로젝트 일정을 시각적으로 관리하세요' }}</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                새 작업
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                내보내기
            </button>
        </div>
    </div>

    <?php
    // 간트 차트 데이터 생성 함수
    $generateGanttData = function() {
        $today = date('Y-m-d');
        
        // 날짜 계산 함수
        $getDateString = function($daysOffset) use ($today) {
            return date('Y-m-d', strtotime($today . ' ' . ($daysOffset >= 0 ? '+' : '') . $daysOffset . ' days'));
        };
        
        // 간트 차트용 작업 데이터
        $tasks = [
            [
                'id' => 'PROJ-1',
                'title' => '프로젝트 초기 설정 및 요구사항 분석',
                'assignee' => '김프로',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => $getDateString(-14),
                'end_date' => $getDateString(-10),
                'progress' => 100,
                'estimated_hours' => 40,
                'actual_hours' => 38,
                'description' => '프로젝트 범위 정의 및 초기 요구사항 수집'
            ],
            [
                'id' => 'PROJ-2',
                'title' => 'UI/UX 디자인 시스템 구축',
                'assignee' => '박디자인',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => $getDateString(-12),
                'end_date' => $getDateString(-6),
                'progress' => 100,
                'estimated_hours' => 60,
                'actual_hours' => 65,
                'description' => 'Design System, 와이어프레임, 프로토타입 제작',
                'dependencies' => ['PROJ-1']
            ],
            [
                'id' => 'PROJ-3',
                'title' => '데이터베이스 스키마 설계',
                'assignee' => '이백엔드',
                'status' => 'completed',
                'priority' => 'high',
                'start_date' => $getDateString(-10),
                'end_date' => $getDateString(-4),
                'progress' => 100,
                'estimated_hours' => 32,
                'actual_hours' => 28,
                'description' => 'ERD 설계 및 데이터베이스 구조 최적화',
                'dependencies' => ['PROJ-1']
            ],
            [
                'id' => 'PROJ-4',
                'title' => '백엔드 API 개발',
                'assignee' => '이백엔드',
                'status' => 'in_progress',
                'priority' => 'high',
                'start_date' => $getDateString(-4),
                'end_date' => $getDateString(8),
                'progress' => 65,
                'estimated_hours' => 80,
                'actual_hours' => 45,
                'description' => 'RESTful API 개발 및 비즈니스 로직 구현',
                'dependencies' => ['PROJ-3']
            ],
            [
                'id' => 'PROJ-5',
                'title' => '프론트엔드 컴포넌트 개발',
                'assignee' => '최프론트',
                'status' => 'in_progress',
                'priority' => 'medium',
                'start_date' => $getDateString(-6),
                'end_date' => $getDateString(6),
                'progress' => 45,
                'estimated_hours' => 70,
                'actual_hours' => 30,
                'description' => 'React 컴포넌트 및 페이지 구현',
                'dependencies' => ['PROJ-2']
            ],
            [
                'id' => 'PROJ-6',
                'title' => '사용자 인증 시스템',
                'assignee' => '정보안',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'start_date' => $getDateString(-2),
                'end_date' => $getDateString(4),
                'progress' => 80,
                'estimated_hours' => 24,
                'actual_hours' => 18,
                'description' => '로그인, 회원가입, 권한 관리 시스템',
                'dependencies' => ['PROJ-4']
            ],
            [
                'id' => 'PROJ-7',
                'title' => '통합 테스트 및 QA',
                'assignee' => '한테스터',
                'status' => 'todo',
                'priority' => 'medium',
                'start_date' => $getDateString(6),
                'end_date' => $getDateString(12),
                'progress' => 0,
                'estimated_hours' => 40,
                'actual_hours' => 0,
                'description' => '전체 시스템 통합 테스트 및 버그 수정',
                'dependencies' => ['PROJ-4', 'PROJ-5']
            ],
            [
                'id' => 'PROJ-8',
                'title' => '배포 및 모니터링 설정',
                'assignee' => '윤데브옵스',
                'status' => 'todo',
                'priority' => 'low',
                'start_date' => $getDateString(10),
                'end_date' => $getDateString(14),
                'progress' => 0,
                'estimated_hours' => 16,
                'actual_hours' => 0,
                'description' => '프로덕션 배포 및 모니터링 시스템 구축',
                'dependencies' => ['PROJ-7']
            ]
        ];
        
        // 시간대 계산
        $allDates = [];
        foreach ($tasks as $task) {
            $allDates[] = $task['start_date'];
            $allDates[] = $task['end_date'];
        }
        
        $minDate = date('Y-m-d', strtotime(min($allDates) . ' -3 days'));
        $maxDate = date('Y-m-d', strtotime(max($allDates) . ' +7 days'));
        
        // 날짜 배열 생성
        $dateColumns = [];
        $currentDate = $minDate;
        while ($currentDate <= $maxDate) {
            $dayOfWeek = date('w', strtotime($currentDate));
            $dateColumns[] = [
                'date' => $currentDate,
                'day' => date('j', strtotime($currentDate)),
                'dayOfWeek' => ['일', '월', '화', '수', '목', '금', '토'][$dayOfWeek],
                'isWeekend' => in_array($dayOfWeek, [0, 6]),
                'isToday' => $currentDate === $today
            ];
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        return [$tasks, $dateColumns];
    };
    
    $getStatusInfo = function($status) {
        $statusMap = [
            'todo' => ['label' => '대기', 'color' => 'bg-gray-400', 'textColor' => 'text-gray-800'],
            'in_progress' => ['label' => '진행중', 'color' => 'bg-blue-500', 'textColor' => 'text-blue-800'],
            'completed' => ['label' => '완료', 'color' => 'bg-green-500', 'textColor' => 'text-green-800'],
            'blocked' => ['label' => '차단', 'color' => 'bg-red-500', 'textColor' => 'text-red-800']
        ];
        return $statusMap[$status] ?? $statusMap['todo'];
    };
    
    $getPriorityInfo = function($priority) {
        $priorityMap = [
            'urgent' => ['label' => '긴급', 'color' => 'text-red-600', 'icon' => '🔴'],
            'high' => ['label' => '높음', 'color' => 'text-orange-600', 'icon' => '🟠'],
            'medium' => ['label' => '보통', 'color' => 'text-blue-600', 'icon' => '🔵'],
            'low' => ['label' => '낮음', 'color' => 'text-gray-600', 'icon' => '⚪']
        ];
        return $priorityMap[$priority] ?? $priorityMap['medium'];
    };
    
    list($tasks, $dateColumns) = $generateGanttData();
    $totalDays = count($dateColumns);
    ?>

    <!-- 통계 요약 -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-green-50 p-3 rounded-lg">
            <div class="font-medium text-green-800">완료</div>
            <div class="text-green-600">{{ count(array_filter($tasks, function($t) { return $t['status'] === 'completed'; })) }}개</div>
        </div>
        <div class="bg-blue-50 p-3 rounded-lg">
            <div class="font-medium text-blue-800">진행중</div>
            <div class="text-blue-600">{{ count(array_filter($tasks, function($t) { return $t['status'] === 'in_progress'; })) }}개</div>
        </div>
        <div class="bg-gray-50 p-3 rounded-lg">
            <div class="font-medium text-gray-800">대기</div>
            <div class="text-gray-600">{{ count(array_filter($tasks, function($t) { return $t['status'] === 'todo'; })) }}개</div>
        </div>
        <div class="bg-red-50 p-3 rounded-lg">
            <div class="font-medium text-red-800">차단됨</div>
            <div class="text-red-600">{{ count(array_filter($tasks, function($t) { return $t['status'] === 'blocked'; })) }}개</div>
        </div>
    </div>

    <!-- 간트 차트 컨테이너 -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- 타임라인 헤더 -->
        <div class="grid grid-cols-12 gap-4 p-3 bg-gray-50 border-b border-gray-200 sticky top-0 z-10">
            <div class="col-span-4">
                <h3 class="font-medium text-gray-900">작업 정보</h3>
            </div>
            <div class="col-span-8">
                <div class="grid gap-1" style="grid-template-columns: repeat({{$totalDays}}, 1fr);">
                    @foreach($dateColumns as $dateCol)
                    <div class="text-xs text-center p-1 {{ $dateCol['isToday'] ? 'bg-blue-100 text-blue-800 font-bold' : ($dateCol['isWeekend'] ? 'text-gray-500' : 'text-gray-700') }}">
                        <div>{{$dateCol['day']}}</div>
                        <div>{{$dateCol['dayOfWeek']}}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 작업 목록 -->
        <div class="max-h-96 overflow-y-auto">
            @foreach($tasks as $task)
            <?php
            $statusInfo = $getStatusInfo($task['status']);
            $priorityInfo = $getPriorityInfo($task['priority']);
            
            // 작업 위치 계산
            $startIndex = -1;
            $endIndex = -1;
            
            foreach ($dateColumns as $index => $dateCol) {
                if ($dateCol['date'] === $task['start_date']) {
                    $startIndex = $index;
                }
                if ($dateCol['date'] === $task['end_date']) {
                    $endIndex = $index;
                }
            }
            
            $validStart = max(0, $startIndex);
            $validEnd = $endIndex >= 0 ? $endIndex : $totalDays - 1;
            $taskDuration = max(1, $validEnd - $validStart + 1);
            ?>
            
            <div class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="grid grid-cols-12 gap-4 p-3">
                    <!-- 작업 정보 (왼쪽 4열) -->
                    <div class="col-span-4 space-y-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-gray-500 font-mono">{{$task['id']}}</span>
                            <span class="{{$priorityInfo['icon']}}"></span>
                        </div>
                        <h4 class="font-medium text-gray-900 cursor-pointer hover:text-blue-600">
                            {{$task['title']}}
                        </h4>
                        <div class="flex items-center gap-3 text-xs text-gray-600">
                            <span class="inline-flex items-center px-2 py-1 rounded {{$statusInfo['color']}} text-white text-xs">
                                {{$statusInfo['label']}}
                            </span>
                            <span>👤 {{$task['assignee']}}</span>
                            <span>⏱️ {{$task['estimated_hours']}}h</span>
                        </div>
                        <!-- 진행률 바 -->
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300 {{$statusInfo['color']}}"
                                     style="width: {{$task['progress']}}%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-8">{{$task['progress']}}%</span>
                        </div>
                    </div>

                    <!-- 타임라인 (오른쪽 8열) -->
                    <div class="col-span-8 relative">
                        <div class="grid gap-1" style="grid-template-columns: repeat({{$totalDays}}, 1fr);">
                            @foreach($dateColumns as $index => $dateCol)
                            <div class="h-12 border-r border-gray-100 relative {{ $dateCol['isToday'] ? 'bg-blue-50' : ($dateCol['isWeekend'] ? 'bg-gray-50' : 'bg-white') }}"
                                 title="{{$dateCol['date']}} ({{$dateCol['dayOfWeek']}})">
                            </div>
                            @endforeach

                            <!-- 작업 막대 -->
                            @if($startIndex >= 0)
                            <div class="absolute top-1 h-10 rounded {{$statusInfo['color']}} opacity-80 flex items-center justify-center text-white text-xs font-medium cursor-pointer hover:opacity-100 transition-opacity"
                                 style="left: {{($validStart * 100) / $totalDays}}%; width: {{($taskDuration * 100) / $totalDays}}%; min-width: 40px"
                                 title="{{$task['title']}} ({{$task['start_date']}} ~ {{$task['end_date']}})">
                                {{$task['progress']}}%
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- 오늘 날짜 표시선 -->
    <div class="mt-4 text-center text-sm text-gray-500">
        <div class="inline-flex items-center gap-2">
            <div class="w-3 h-3 bg-blue-500 rounded"></div>
            <span>오늘: {{date('Y-m-d')}}</span>
        </div>
    </div>

    <!-- 범례 -->
    <div class="mt-6 flex flex-wrap items-center gap-4 text-sm">
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-green-500 rounded"></div>
            <span>완료</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-blue-500 rounded"></div>
            <span>진행중</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-gray-400 rounded"></div>
            <span>대기</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-red-500 rounded"></div>
            <span>차단됨</span>
        </div>
        <div class="ml-8 flex items-center gap-4">
            <span>우선순위:</span>
            <span>🔴 긴급</span>
            <span>🟠 높음</span>
            <span>🔵 보통</span>
            <span>⚪ 낮음</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 작업 클릭 이벤트
    document.addEventListener('click', function(e) {
        const taskTitle = e.target.closest('h4');
        const taskBar = e.target.closest('.opacity-80');
        
        if (taskTitle || taskBar) {
            const taskRow = e.target.closest('.grid');
            const taskId = taskRow ? taskRow.querySelector('.font-mono').textContent : '';
            console.log('작업 클릭:', taskId);
            // 여기서 작업 상세 모달을 열 수 있습니다
        }
    });
    
    // 작업 막대 hover 효과
    document.addEventListener('mouseover', function(e) {
        if (e.target.closest('.opacity-80')) {
            e.target.style.opacity = '1';
            e.target.style.transform = 'scale(1.02)';
        }
    });
    
    document.addEventListener('mouseout', function(e) {
        if (e.target.closest('.opacity-80')) {
            e.target.style.opacity = '0.8';
            e.target.style.transform = 'scale(1)';
        }
    });
    
    // 새로고침 기능
    window.refreshGanttChart = function() {
        location.reload();
    };
    
    // 줌 기능 (향후 구현 가능)
    window.zoomGanttChart = function(level) {
        console.log('Zoom level:', level);
        // 줌 로직 구현
    };
});
</script>