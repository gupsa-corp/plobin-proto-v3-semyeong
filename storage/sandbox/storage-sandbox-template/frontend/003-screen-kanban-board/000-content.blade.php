<div class="bg-gray-100 p-6 rounded-lg min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title ?? '프로젝트 칸반 보드' }}</h2>
            <p class="text-gray-600">{{ $description ?? '드래그 앤 드롭으로 작업을 관리하세요' }}</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                새 작업 추가
            </button>
            <button class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                </svg>
                필터
            </button>
        </div>
    </div>

    <?php
    // 칸반 보드 데이터 생성 함수
    $generateKanbanData = function() {
        $columns = [
            'backlog' => [
                'title' => '백로그',
                'color' => 'bg-gray-200',
                'icon' => '📋'
            ],
            'todo' => [
                'title' => '할 일',
                'color' => 'bg-red-100',
                'icon' => '📝'
            ],
            'in_progress' => [
                'title' => '진행 중',
                'color' => 'bg-blue-100',
                'icon' => '🔄'
            ],
            'review' => [
                'title' => '검토 중',
                'color' => 'bg-yellow-100',
                'icon' => '👁️'
            ],
            'testing' => [
                'title' => '테스트',
                'color' => 'bg-purple-100',
                'icon' => '🧪'
            ],
            'done' => [
                'title' => '완료',
                'color' => 'bg-green-100',
                'icon' => '✅'
            ]
        ];

        $priorities = [
            'urgent' => ['label' => '긴급', 'color' => 'bg-red-500', 'text' => 'text-white'],
            'high' => ['label' => '높음', 'color' => 'bg-orange-500', 'text' => 'text-white'],
            'medium' => ['label' => '보통', 'color' => 'bg-blue-500', 'text' => 'text-white'],
            'low' => ['label' => '낮음', 'color' => 'bg-gray-500', 'text' => 'text-white']
        ];

        $assignees = [
            ['name' => '김개발', 'avatar' => 'bg-blue-500'],
            ['name' => '이디자인', 'avatar' => 'bg-green-500'],
            ['name' => '박기획', 'avatar' => 'bg-purple-500'],
            ['name' => '최테스터', 'avatar' => 'bg-red-500'],
            ['name' => '정PM', 'avatar' => 'bg-yellow-500']
        ];

        $tasks = [
            // 백로그
            [
                'id' => 'TASK-001',
                'title' => '사용자 프로필 페이지 개선',
                'description' => '사용자 경험 향상을 위한 프로필 페이지 리뉴얼',
                'status' => 'backlog',
                'priority' => 'medium',
                'assignee' => $assignees[0],
                'due_date' => '2024-10-15',
                'tags' => ['UI/UX', '프론트엔드'],
                'comments' => 3
            ],
            [
                'id' => 'TASK-002',
                'title' => '데이터베이스 백업 자동화',
                'description' => '주기적 백업 스케줄링 시스템 구축',
                'status' => 'backlog',
                'priority' => 'low',
                'assignee' => $assignees[3],
                'due_date' => '2024-10-20',
                'tags' => ['백엔드', '인프라'],
                'comments' => 1
            ],

            // 할 일
            [
                'id' => 'TASK-003',
                'title' => '모바일 반응형 디자인',
                'description' => '모든 페이지의 모바일 최적화 작업',
                'status' => 'todo',
                'priority' => 'high',
                'assignee' => $assignees[1],
                'due_date' => '2024-09-25',
                'tags' => ['모바일', 'CSS'],
                'comments' => 5
            ],
            [
                'id' => 'TASK-004',
                'title' => 'API 문서화',
                'description' => 'Swagger를 이용한 API 문서 자동 생성',
                'status' => 'todo',
                'priority' => 'medium',
                'assignee' => $assignees[0],
                'due_date' => '2024-09-30',
                'tags' => ['문서', 'API'],
                'comments' => 2
            ],

            // 진행 중
            [
                'id' => 'TASK-005',
                'title' => '사용자 인증 시스템',
                'description' => 'JWT 기반 로그인/회원가입 구현',
                'status' => 'in_progress',
                'priority' => 'urgent',
                'assignee' => $assignees[0],
                'due_date' => '2024-09-20',
                'tags' => ['보안', '백엔드'],
                'comments' => 8
            ],
            [
                'id' => 'TASK-006',
                'title' => '대시보드 차트 구현',
                'description' => '실시간 데이터 시각화 컴포넌트',
                'status' => 'in_progress',
                'priority' => 'high',
                'assignee' => $assignees[1],
                'due_date' => '2024-09-18',
                'tags' => ['차트', '프론트엔드'],
                'comments' => 4
            ],

            // 검토 중
            [
                'id' => 'TASK-007',
                'title' => '이메일 알림 시스템',
                'description' => '사용자 액션에 따른 이메일 발송',
                'status' => 'review',
                'priority' => 'medium',
                'assignee' => $assignees[2],
                'due_date' => '2024-09-22',
                'tags' => ['이메일', '알림'],
                'comments' => 6
            ],

            // 테스트
            [
                'id' => 'TASK-008',
                'title' => '결제 시스템 통합',
                'description' => '외부 결제 API 연동 및 테스트',
                'status' => 'testing',
                'priority' => 'urgent',
                'assignee' => $assignees[3],
                'due_date' => '2024-09-16',
                'tags' => ['결제', 'API'],
                'comments' => 12
            ],
            [
                'id' => 'TASK-009',
                'title' => '성능 최적화',
                'description' => '페이지 로딩 속도 개선',
                'status' => 'testing',
                'priority' => 'high',
                'assignee' => $assignees[0],
                'due_date' => '2024-09-19',
                'tags' => ['성능', '최적화'],
                'comments' => 3
            ],

            // 완료
            [
                'id' => 'TASK-010',
                'title' => '로고 및 브랜딩',
                'description' => '새로운 회사 로고 및 브랜드 가이드',
                'status' => 'done',
                'priority' => 'medium',
                'assignee' => $assignees[1],
                'due_date' => '2024-09-10',
                'tags' => ['디자인', '브랜딩'],
                'comments' => 7
            ],
            [
                'id' => 'TASK-011',
                'title' => '프로젝트 초기 설정',
                'description' => '개발 환경 및 CI/CD 파이프라인 구축',
                'status' => 'done',
                'priority' => 'high',
                'assignee' => $assignees[4],
                'due_date' => '2024-09-05',
                'tags' => ['인프라', 'DevOps'],
                'comments' => 9
            ]
        ];

        return [$columns, $tasks, $priorities];
    };

    list($columns, $tasks, $priorities) = $generateKanbanData();
    ?>

    <!-- 칸반 보드 컨테이너 -->
    <div class="flex space-x-6 overflow-x-auto pb-6">
        @foreach($columns as $columnId => $column)
        <div class="flex-shrink-0 w-80">
            <!-- 컬럼 헤더 -->
            <div class="{{$column['color']}} rounded-lg p-4 mb-4 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-lg">{{$column['icon']}}</span>
                        <h3 class="font-semibold text-gray-800">{{$column['title']}}</h3>
                        <span class="bg-white bg-opacity-50 text-gray-700 text-sm px-2 py-1 rounded-full">
                            {{ count(array_filter($tasks, function($task) use ($columnId) { return $task['status'] === $columnId; })) }}
                        </span>
                    </div>
                    <button class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- 작업 카드들 -->
            <div class="space-y-3 min-h-96" data-column="{{$columnId}}">
                @foreach(array_filter($tasks, function($task) use ($columnId) { return $task['status'] === $columnId; }) as $task)
                <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow cursor-pointer border border-gray-200 task-card" 
                     data-task-id="{{$task['id']}}" draggable="true">
                    <div class="p-4">
                        <!-- 작업 헤더 -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs font-mono text-gray-500">{{$task['id']}}</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{$priorities[$task['priority']]['color']}} {{$priorities[$task['priority']]['text']}}">
                                        {{$priorities[$task['priority']]['label']}}
                                    </span>
                                </div>
                                <h4 class="font-medium text-gray-900 mb-2">{{$task['title']}}</h4>
                                <p class="text-sm text-gray-600 mb-3">{{$task['description']}}</p>
                            </div>
                        </div>

                        <!-- 태그 -->
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach($task['tags'] as $tag)
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                {{$tag}}
                            </span>
                            @endforeach
                        </div>

                        <!-- 작업 하단 정보 -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <!-- 담당자 아바타 -->
                                <div class="flex items-center space-x-1">
                                    <div class="h-6 w-6 rounded-full {{$task['assignee']['avatar']}} flex items-center justify-center text-white text-xs font-medium">
                                        {{ mb_substr($task['assignee']['name'], 0, 1) }}
                                    </div>
                                    <span class="text-xs text-gray-600">{{$task['assignee']['name']}}</span>
                                </div>
                                
                                <!-- 댓글 수 -->
                                <div class="flex items-center space-x-1 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    <span class="text-xs">{{$task['comments']}}</span>
                                </div>
                            </div>

                            <!-- 마감일 -->
                            <div class="flex items-center space-x-1 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs">{{date('m/d', strtotime($task['due_date']))}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <!-- 새 작업 추가 버튼 -->
                <button class="w-full p-4 border-2 border-dashed border-gray-300 rounded-lg text-gray-500 hover:border-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="text-sm">작업 추가</span>
                </button>
            </div>
        </div>
        @endforeach
    </div>

    <!-- 통계 요약 -->
    <div class="mt-8 grid grid-cols-2 md:grid-cols-6 gap-4">
        @foreach($columns as $columnId => $column)
        <div class="{{$column['color']}} p-4 rounded-lg text-center">
            <div class="text-2xl mb-1">{{$column['icon']}}</div>
            <div class="text-sm font-medium text-gray-700">{{$column['title']}}</div>
            <div class="text-xl font-bold text-gray-800">
                {{ count(array_filter($tasks, function($task) use ($columnId) { return $task['status'] === $columnId; })) }}
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.task-card.dragging {
    opacity: 0.5;
    transform: rotate(5deg);
}

.drop-zone-active {
    background-color: #dbeafe;
    border: 2px dashed #3b82f6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let draggedElement = null;
    
    // 드래그 시작
    document.addEventListener('dragstart', function(e) {
        if (e.target.classList.contains('task-card')) {
            draggedElement = e.target;
            e.target.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
            e.dataTransfer.setData('text/html', e.target.outerHTML);
        }
    });
    
    // 드래그 종료
    document.addEventListener('dragend', function(e) {
        if (e.target.classList.contains('task-card')) {
            e.target.classList.remove('dragging');
            // 모든 drop-zone-active 클래스 제거
            document.querySelectorAll('.drop-zone-active').forEach(zone => {
                zone.classList.remove('drop-zone-active');
            });
        }
    });
    
    // 드래그 오버
    document.addEventListener('dragover', function(e) {
        e.preventDefault();
        const column = e.target.closest('[data-column]');
        if (column) {
            column.classList.add('drop-zone-active');
        }
    });
    
    // 드래그 리브
    document.addEventListener('dragleave', function(e) {
        const column = e.target.closest('[data-column]');
        if (column && !column.contains(e.relatedTarget)) {
            column.classList.remove('drop-zone-active');
        }
    });
    
    // 드롭
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        const column = e.target.closest('[data-column]');
        
        if (column && draggedElement) {
            const columnId = column.getAttribute('data-column');
            const taskId = draggedElement.getAttribute('data-task-id');
            
            // 카드를 새 컬럼에 추가
            column.insertBefore(draggedElement, column.querySelector('button'));
            column.classList.remove('drop-zone-active');
            
            console.log(`작업 ${taskId}을(를) ${columnId} 컬럼으로 이동했습니다.`);
            
            // 여기서 서버로 상태 업데이트 요청을 보낼 수 있습니다
            // updateTaskStatus(taskId, columnId);
        }
        
        draggedElement = null;
    });
    
    // 작업 카드 클릭 이벤트
    document.addEventListener('click', function(e) {
        const taskCard = e.target.closest('.task-card');
        if (taskCard) {
            const taskId = taskCard.getAttribute('data-task-id');
            console.log(`작업 ${taskId} 상세 보기`);
            // 여기서 작업 상세 모달을 열 수 있습니다
        }
    });
    
    // 새 작업 추가 버튼 클릭
    document.addEventListener('click', function(e) {
        if (e.target.closest('button') && e.target.textContent.includes('작업 추가')) {
            const column = e.target.closest('[data-column]');
            const columnId = column.getAttribute('data-column');
            console.log(`${columnId} 컬럼에 새 작업 추가`);
            // 여기서 새 작업 추가 모달을 열 수 있습니다
        }
    });
});
</script>