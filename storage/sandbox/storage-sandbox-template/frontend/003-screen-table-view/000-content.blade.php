{{-- 샌드박스 테이블 뷰 템플릿 --}}
<div class="min-h-screen bg-gray-50 p-6">
    {{-- 헤더 --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <span class="text-purple-600">🗂️</span>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">프로젝트 테이블 뷰</h1>
                    <p class="text-gray-600">데이터를 표 형태로 체계적으로 관리하세요</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">필터</button>
                <button class="px-4 py-2 text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">내보내기</button>
            </div>
        </div>
    </div>

    {{-- 필터 바 --}}
    <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
        <div class="flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-64">
                <input type="text" placeholder="프로젝트명, 담당자 검색..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            </div>
            <select class="px-3 py-2 border border-gray-300 rounded-lg">
                <option>모든 상태</option>
                <option>진행 중</option>
                <option>완료</option>
                <option>보류</option>
            </select>
            <select class="px-3 py-2 border border-gray-300 rounded-lg">
                <option>모든 우선순위</option>
                <option>높음</option>
                <option>보통</option>
                <option>낮음</option>
            </select>
            <button class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">검색</button>
        </div>
    </div>

    {{-- 테이블 --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" class="rounded">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            프로젝트명 ↕️
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            상태
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            진행률
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            담당자
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            마감일 ↕️
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            우선순위
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            액션
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        // screen 파라미터에 따라 다른 데이터 세트 정의
                        $screenId = $screenId ?? request()->get('screen', '');
                        
                        if ($screenId === '01c4f4304b6bd4325479dc32037e6cf0') {
                            // 대체 데이터 세트
                            $projectsData = [
                                ['name' => '프로젝트 1', 'status' => '보류', 'progress' => 26, 'team' => 8, 'date' => '2025-08-16'],
                                ['name' => '프로젝트 2', 'status' => '완료', 'progress' => 74, 'team' => 5, 'date' => '2025-08-29'],
                                ['name' => '프로젝트 3', 'status' => '계획', 'progress' => 100, 'team' => 7, 'date' => '2025-08-19'],
                                ['name' => '프로젝트 4', 'status' => '완료', 'progress' => 53, 'team' => 2, 'date' => '2025-08-12'],
                                ['name' => '프로젝트 5', 'status' => '진행 중', 'progress' => 42, 'team' => 8, 'date' => '2025-08-17'],
                                ['name' => '프로젝트 6', 'status' => '계획', 'progress' => 29, 'team' => 3, 'date' => '2025-08-20'],
                                ['name' => '프로젝트 7', 'status' => '보류', 'progress' => 64, 'team' => 3, 'date' => '2025-08-24'],
                                ['name' => '프로젝트 8', 'status' => '진행 중', 'progress' => 80, 'team' => 3, 'date' => '2025-08-22'],
                                ['name' => '프로젝트 9', 'status' => '진행 중', 'progress' => 86, 'team' => 5, 'date' => '2025-09-09'],
                            ];
                        } else {
                            // 기본 데이터 세트 (screen=2059a206aa5bcf8f404e5ae486859b73 또는 빈값)
                            $projectsData = [
                                ['name' => '프로젝트 1', 'status' => '완료', 'progress' => 59, 'team' => 8, 'date' => '2025-08-25'],
                                ['name' => '프로젝트 2', 'status' => '진행 중', 'progress' => 44, 'team' => 7, 'date' => '2025-09-07'],
                                ['name' => '프로젝트 3', 'status' => '완료', 'progress' => 43, 'team' => 5, 'date' => '2025-08-27'],
                                ['name' => '프로젝트 4', 'status' => '진행 중', 'progress' => 75, 'team' => 8, 'date' => '2025-08-26'],
                                ['name' => '프로젝트 5', 'status' => '계획', 'progress' => 85, 'team' => 5, 'date' => '2025-08-24'],
                                ['name' => '프로젝트 6', 'status' => '계획', 'progress' => 28, 'team' => 8, 'date' => '2025-09-07'],
                                ['name' => '프로젝트 7', 'status' => '진행 중', 'progress' => 84, 'team' => 7, 'date' => '2025-08-12'],
                                ['name' => '프로젝트 8', 'status' => '보류', 'progress' => 95, 'team' => 3, 'date' => '2025-09-05'],
                                ['name' => '프로젝트 9', 'status' => '보류', 'progress' => 48, 'team' => 4, 'date' => '2025-09-04'],
                            ];
                        }
                        
                        $statusColors = [
                            '진행 중' => 'bg-blue-100 text-blue-800',
                            '완료' => 'bg-green-100 text-green-800',
                            '보류' => 'bg-yellow-100 text-yellow-800',
                            '계획' => 'bg-purple-100 text-purple-800'
                        ];
                        $priorities = ['높음', '보통', '낮음'];
                        $priorityColors = [
                            '높음' => 'bg-red-100 text-red-800',
                            '보통' => 'bg-yellow-100 text-yellow-800',
                            '낮음' => 'bg-green-100 text-green-800'
                        ];
                    @endphp
                    
                    @foreach($projectsData as $i => $project)
                        @php 
                            $priority = $priorities[array_rand($priorities)];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-gray-600 text-sm">P{{ $i + 1 }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $project['name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $project['name'] }}에 대한 상세 설명입니다. 이 프로젝트는 현재 {{ $project['status'] }} 상태입니다.</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$project['status']] }}">
                                    {{ $project['status'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $project['progress'] }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $project['progress'] }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full mr-2"></div>
                                    <div class="text-sm text-gray-900">팀 멤버 {{ $project['team'] }}명</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                생성일: {{ $project['date'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $priorityColors[$priority] }}">
                                    {{ $priority }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900">보기</button>
                                    <button class="text-green-600 hover:text-green-900">편집</button>
                                    <button class="text-red-600 hover:text-red-900">삭제</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{-- 페이지네이션 --}}
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    총 <span class="font-medium">{{ rand(50, 200) }}</span>개 중 <span class="font-medium">1-10</span> 표시
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">이전</button>
                    <button class="px-3 py-2 text-sm bg-purple-600 text-white rounded-lg">1</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">2</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">3</button>
                    <button class="px-3 py-2 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">다음</button>
                </div>
            </div>
        </div>
    </div>
</div>