<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title ?? '프로젝트 테이블 뷰' }}</h2>
            <p class="text-gray-600">{{ $description ?? '프로젝트 데이터를 테이블 형식으로 관리하세요' }}</p>
        </div>
        <div class="flex space-x-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                새 항목 추가
            </button>
            <button class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                내보내기
            </button>
        </div>
    </div>

    <!-- 필터 및 검색 -->
    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">검색</label>
                <input type="text" 
                       placeholder="프로젝트명 또는 설명 검색..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">상태</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">모든 상태</option>
                    <option value="planning">기획 중</option>
                    <option value="in_progress">진행 중</option>
                    <option value="testing">테스트</option>
                    <option value="completed">완료</option>
                    <option value="on_hold">보류</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">우선순위</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">모든 우선순위</option>
                    <option value="urgent">긴급</option>
                    <option value="high">높음</option>
                    <option value="medium">보통</option>
                    <option value="low">낮음</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">담당자</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">모든 담당자</option>
                    <option value="kim">김개발</option>
                    <option value="lee">이디자인</option>
                    <option value="park">박기획</option>
                </select>
            </div>
        </div>
    </div>

    <!-- 테이블 -->
    <div class="overflow-x-auto bg-white rounded-lg shadow border">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <input type="checkbox" class="rounded border-gray-300">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        프로젝트명
                        <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">우선순위</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">담당자</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">진행률</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                        시작일
                        <svg class="w-3 h-3 inline ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                        </svg>
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">마감일</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                // 샘플 데이터 생성 함수
                $generateTableData = function() {
                    $statuses = [
                        'planning' => ['label' => '기획 중', 'color' => 'bg-yellow-100 text-yellow-800'],
                        'in_progress' => ['label' => '진행 중', 'color' => 'bg-blue-100 text-blue-800'],
                        'testing' => ['label' => '테스트', 'color' => 'bg-purple-100 text-purple-800'],
                        'completed' => ['label' => '완료', 'color' => 'bg-green-100 text-green-800'],
                        'on_hold' => ['label' => '보류', 'color' => 'bg-gray-100 text-gray-800']
                    ];
                    
                    $priorities = [
                        'urgent' => ['label' => '긴급', 'color' => 'text-red-600', 'icon' => '🔴'],
                        'high' => ['label' => '높음', 'color' => 'text-orange-600', 'icon' => '🟠'],
                        'medium' => ['label' => '보통', 'color' => 'text-blue-600', 'icon' => '🔵'],
                        'low' => ['label' => '낮음', 'color' => 'text-gray-600', 'icon' => '⚪']
                    ];
                    
                    $assignees = ['김개발', '이디자인', '박기획', '최테스터', '정PM'];
                    
                    $projects = [
                        ['name' => '사용자 인증 시스템 개발', 'description' => 'JWT 기반 인증 시스템 구축'],
                        ['name' => '대시보드 UI 리뉴얼', 'description' => '모던한 대시보드 인터페이스 디자인'],
                        ['name' => 'API 성능 최적화', 'description' => '데이터베이스 쿼리 및 API 응답 속도 개선'],
                        ['name' => '모바일 앱 버그 수정', 'description' => '크리티컬 버그 20건 수정'],
                        ['name' => '데이터 백업 시스템', 'description' => '자동 백업 및 복원 시스템 구축'],
                        ['name' => '사용자 피드백 시스템', 'description' => '고객 의견 수집 및 분석 도구'],
                        ['name' => '보안 감사 및 개선', 'description' => '전체 시스템 보안 점검 및 강화'],
                        ['name' => '성능 모니터링 도구', 'description' => '실시간 시스템 모니터링 구축']
                    ];
                    
                    $tableData = [];
                    foreach ($projects as $index => $project) {
                        $statusKeys = array_keys($statuses);
                        $priorityKeys = array_keys($priorities);
                        
                        $startDate = date('Y-m-d', strtotime('-' . rand(30, 90) . ' days'));
                        $endDate = date('Y-m-d', strtotime($startDate . ' +' . rand(30, 120) . ' days'));
                        
                        $tableData[] = [
                            'id' => 'PROJ-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                            'name' => $project['name'],
                            'description' => $project['description'],
                            'status' => $statusKeys[array_rand($statusKeys)],
                            'priority' => $priorityKeys[array_rand($priorityKeys)],
                            'assignee' => $assignees[array_rand($assignees)],
                            'progress' => rand(0, 100),
                            'start_date' => $startDate,
                            'end_date' => $endDate,
                            'budget' => rand(100, 1000) . '만원'
                        ];
                    }
                    
                    return [$tableData, $statuses, $priorities];
                };
                
                list($tableData, $statuses, $priorities) = $generateTableData();
                ?>
                
                @foreach($tableData as $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <input type="checkbox" class="rounded border-gray-300" value="{{ $row['id'] }}">
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $row['name'] }}</div>
                        <div class="text-sm text-gray-500">{{ $row['description'] }}</div>
                        <div class="text-xs text-gray-400 mt-1">ID: {{ $row['id'] }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statuses[$row['status']]['color'] }}">
                            {{ $statuses[$row['status']]['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="mr-1">{{ $priorities[$row['priority']]['icon'] }}</span>
                            <span class="text-sm font-medium {{ $priorities[$row['priority']]['color'] }}">
                                {{ $priorities[$row['priority']]['label'] }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-sm font-medium mr-2">
                                {{ mb_substr($row['assignee'], 0, 1) }}
                            </div>
                            <span class="text-sm text-gray-900">{{ $row['assignee'] }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-1 bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $row['progress'] }}%"></div>
                            </div>
                            <span class="text-sm text-gray-600 w-10">{{ $row['progress'] }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $row['start_date'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $row['end_date'] }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end space-x-2">
                            <button class="text-blue-600 hover:text-blue-900 transition-colors" title="편집">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button class="text-green-600 hover:text-green-900 transition-colors" title="보기">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900 transition-colors" title="삭제">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- 페이지네이션 -->
    <div class="mt-6 flex items-center justify-between">
        <div class="flex items-center text-sm text-gray-500">
            <span>전체 {{ count($tableData) }}개 항목 중 1-{{ count($tableData) }}개 표시</span>
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50 disabled:opacity-50" disabled>
                이전
            </button>
            <button class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">1</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border border-gray-300 rounded-md text-sm text-gray-500 hover:bg-gray-50">
                다음
            </button>
        </div>
    </div>

    <!-- 요약 통계 -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-blue-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-blue-800">진행 중인 프로젝트</div>
            <div class="text-2xl font-bold text-blue-600">
                {{ count(array_filter($tableData, function($item) { return $item['status'] === 'in_progress'; })) }}개
            </div>
        </div>
        <div class="bg-green-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-green-800">완료된 프로젝트</div>
            <div class="text-2xl font-bold text-green-600">
                {{ count(array_filter($tableData, function($item) { return $item['status'] === 'completed'; })) }}개
            </div>
        </div>
        <div class="bg-yellow-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-yellow-800">기획 중인 프로젝트</div>
            <div class="text-2xl font-bold text-yellow-600">
                {{ count(array_filter($tableData, function($item) { return $item['status'] === 'planning'; })) }}개
            </div>
        </div>
        <div class="bg-purple-50 p-4 rounded-lg">
            <div class="text-sm font-medium text-purple-800">평균 진행률</div>
            <div class="text-2xl font-bold text-purple-600">
                {{ round(array_sum(array_column($tableData, 'progress')) / count($tableData)) }}%
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 테이블 정렬 기능
    const sortableHeaders = document.querySelectorAll('th.cursor-pointer');
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            console.log('정렬 클릭:', this.textContent.trim());
            // 여기서 정렬 로직 구현 가능
        });
    });
    
    // 체크박스 전체 선택
    const masterCheckbox = document.querySelector('thead input[type="checkbox"]');
    const rowCheckboxes = document.querySelectorAll('tbody input[type="checkbox"]');
    
    if (masterCheckbox) {
        masterCheckbox.addEventListener('change', function() {
            rowCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
    
    // 필터 기능
    const filterInputs = document.querySelectorAll('input, select');
    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            console.log('필터 변경:', this.value);
            // 여기서 필터링 로직 구현 가능
        });
    });
});
</script>