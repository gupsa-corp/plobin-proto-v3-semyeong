<main class="service-main flex-1 p-6">
    <!-- 인증 체크 로딩 -->
    <div id="authLoading" class="flex items-center justify-center min-h-96">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
        <span class="ml-3 text-gray-600">인증 확인 중...</span>
    </div>

    <!-- 인증되지 않은 사용자 -->
    <div id="authRequired" class="hidden text-center py-20">
        <div class="mb-6">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">로그인이 필요합니다</h2>
        <p class="text-gray-600 mb-6">대시보드를 사용하려면 로그인해주세요.</p>
        <div class="space-x-4">
            <a href="/login" class="bg-blue-500 text-white px-6 py-3 rounded-md hover:bg-blue-600 transition duration-200">
                로그인
            </a>
            <a href="/signup" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-md hover:bg-gray-200 transition duration-200">
                회원가입
            </a>
        </div>
    </div>

    <!-- 메인 대시보드 콘텐츠 -->
    <div id="dashboardContent" class="hidden">
        <!-- 헤더 -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">대시보드</h1>
            <p class="text-gray-600">환영합니다! <span id="userName">사용자</span>님</p>
        </div>

    <!-- 통계 카드 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="service-card p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">총 프로젝트</p>
                    <p class="text-2xl font-bold text-gray-900">12</p>
                </div>
            </div>
        </div>

        <div class="service-card p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">완료된 작업</p>
                    <p class="text-2xl font-bold text-gray-900">47</p>
                </div>
            </div>
        </div>

        <div class="service-card p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">진행 중</p>
                    <p class="text-2xl font-bold text-gray-900">8</p>
                </div>
            </div>
        </div>

        <div class="service-card p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">팀 멤버</p>
                    <p class="text-2xl font-bold text-gray-900">23</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 메인 콘텐츠 그리드 -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 최근 프로젝트 -->
        <div class="lg:col-span-2">
            <div class="service-card">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">최근 프로젝트</h3>
                        <button class="btn-service text-sm px-3 py-1">모두 보기</button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    P1
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">웹사이트 리뉴얼</p>
                                    <p class="text-sm text-gray-500">3일 전 업데이트</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">진행중</span>
                                <span class="ml-2 text-sm text-gray-500">75%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    P2
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">모바일 앱 개발</p>
                                    <p class="text-sm text-gray-500">1주 전 업데이트</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-medium text-blue-800 bg-blue-100 rounded-full">대기중</span>
                                <span class="ml-2 text-sm text-gray-500">30%</span>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center text-white font-bold">
                                    P3
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">API 통합 작업</p>
                                    <p class="text-sm text-gray-500">어제 완료</p>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">완료</span>
                                <span class="ml-2 text-sm text-gray-500">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 팀 활동 -->
        <div class="lg:col-span-1">
            <div class="service-card">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">팀 활동</h3>
                </div>
                
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                김
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">김개발자님이 새로운 기능을 완료했습니다.</p>
                                <p class="text-xs text-gray-500">2시간 전</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                박
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">박디자이너님이 UI 디자인을 업데이트했습니다.</p>
                                <p class="text-xs text-gray-500">5시간 전</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                이
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm text-gray-900">이매니저님이 새 프로젝트를 생성했습니다.</p>
                                <p class="text-xs text-gray-500">1일 전</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    {{-- AJAX 로직 포함 --}}
    @include('301-service-dashboard.ajax')
    
    {{-- JavaScript 로직 포함 --}}
    @include('301-service-dashboard.javascript')
</main>