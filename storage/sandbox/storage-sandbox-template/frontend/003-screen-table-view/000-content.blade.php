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
                    @for($i = 1; $i <= 10; $i++)
                        @php 
                            $statuses = ['진행 중', '완료', '보류', '검토 중'];
                            $priorities = ['높음', '보통', '낮음'];
                            $status = $statuses[array_rand($statuses)];
                            $priority = $priorities[array_rand($priorities)];
                            $progress = rand(20, 100);
                            $statusColors = [
                                '진행 중' => 'bg-blue-100 text-blue-800',
                                '완료' => 'bg-green-100 text-green-800',
                                '보류' => 'bg-yellow-100 text-yellow-800',
                                '검토 중' => 'bg-purple-100 text-purple-800'
                            ];
                            $priorityColors = [
                                '높음' => 'bg-red-100 text-red-800',
                                '보통' => 'bg-yellow-100 text-yellow-800',
                                '낮음' => 'bg-green-100 text-green-800'
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" class="rounded">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                        <span class="text-gray-600 text-sm">P{{ $i }}</span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">프로젝트 {{ $i }}</div>
                                        <div class="text-sm text-gray-500">{{ $i }}번째 프로젝트</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$status] }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">{{ $progress }}%</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-300 rounded-full mr-2"></div>
                                    <div class="text-sm text-gray-900">담당자{{ $i }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ now()->addDays(rand(1, 30))->format('Y-m-d') }}
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
                    @endfor
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