<div class="bg-white p-6 rounded-lg shadow-md">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">프로젝트 관리</h2>
            <p class="text-gray-600">모든 프로젝트를 관리하고 모니터링할 수 있습니다.</p>
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            새 프로젝트
        </button>
    </div>
    
    <!-- 필터 및 검색 -->
    <div class="mb-6 flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" placeholder="프로젝트 이름으로 검색..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div class="flex gap-2">
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">전체 상태</option>
                <option value="active">진행중</option>
                <option value="completed">완료</option>
                <option value="paused">일시중단</option>
            </select>
            <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">전체 팀</option>
                <option value="dev">개발팀</option>
                <option value="design">디자인팀</option>
                <option value="marketing">마케팅팀</option>
            </select>
        </div>
    </div>

    <!-- 프로젝트 통계 카드 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-blue-600">전체 프로젝트</p>
                    <p class="text-2xl font-bold text-blue-800">24</p>
                </div>
                <div class="p-2 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-green-600">진행중</p>
                    <p class="text-2xl font-bold text-green-800">18</p>
                </div>
                <div class="p-2 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-yellow-600">대기중</p>
                    <p class="text-2xl font-bold text-yellow-800">4</p>
                </div>
                <div class="p-2 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-purple-600">완료</p>
                    <p class="text-2xl font-bold text-purple-800">2</p>
                </div>
                <div class="p-2 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 프로젝트 테이블 -->
    <div class="overflow-x-auto">
        <table class="min-w-full table-auto">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        프로젝트명
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        팀/조직
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        진행률
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        상태
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        마감일
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        작업
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <!-- 샘플 프로젝트 1 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-semibold">W</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">웹 사이트 리뉴얼</div>
                                <div class="text-sm text-gray-500">메인 웹사이트 전면 개편</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        개발팀
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-sm text-gray-600">75%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            진행중
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        2025-10-15
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900">편집</button>
                        <button class="text-green-600 hover:text-green-900">보기</button>
                        <button class="text-red-600 hover:text-red-900">삭제</button>
                    </td>
                </tr>
                
                <!-- 샘플 프로젝트 2 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-green-600 font-semibold">M</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">모바일 앱 개발</div>
                                <div class="text-sm text-gray-500">iOS/Android 네이티브 앱</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        모바일팀
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-green-600 h-2 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="text-sm text-gray-600">45%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            계획중
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        2025-12-01
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900">편집</button>
                        <button class="text-green-600 hover:text-green-900">보기</button>
                        <button class="text-red-600 hover:text-red-900">삭제</button>
                    </td>
                </tr>
                
                <!-- 샘플 프로젝트 3 -->
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-purple-600 font-semibold">A</span>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">API 플랫폼 구축</div>
                                <div class="text-sm text-gray-500">RESTful API 서비스 구축</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        백엔드팀
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                <div class="bg-purple-600 h-2 rounded-full" style="width: 90%"></div>
                            </div>
                            <span class="text-sm text-gray-600">90%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            검토중
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        2025-09-30
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                        <button class="text-blue-600 hover:text-blue-900">편집</button>
                        <button class="text-green-600 hover:text-green-900">보기</button>
                        <button class="text-red-600 hover:text-red-900">삭제</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- 페이지네이션 -->
    <div class="mt-6 flex items-center justify-between">
        <div class="text-sm text-gray-700">
            <span class="font-medium">1</span>에서 <span class="font-medium">3</span>까지, 총 <span class="font-medium">24</span>개 프로젝트 중
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 text-sm text-gray-500 border border-gray-300 rounded hover:bg-gray-50">
                이전
            </button>
            <button class="px-3 py-1 text-sm text-white bg-blue-600 border border-blue-600 rounded">
                1
            </button>
            <button class="px-3 py-1 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                2
            </button>
            <button class="px-3 py-1 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                3
            </button>
            <button class="px-3 py-1 text-sm text-gray-700 border border-gray-300 rounded hover:bg-gray-50">
                다음
            </button>
        </div>
    </div>
</div>