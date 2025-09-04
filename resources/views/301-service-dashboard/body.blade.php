<main class="service-main flex-1 p-6">
    {{-- 인증 체크 컴포넌트 --}}
    @include('501-service-block-auth-check.auth-check')

    {{-- 대시보드 메인 콘텐츠 --}}
    <div id="dashboardContent" class="hidden">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">대시보드</h1>
            <p class="mt-2 text-gray-600">안녕하세요, <span id="userName">사용자</span>님!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- 통계 카드들 -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">프로젝트</p>
                        <p class="text-2xl font-bold text-gray-900">3</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">최근 활동</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-500">최근 활동이 없습니다.</p>
            </div>
        </div>
    </div>

    {{-- AJAX 로직 포함 --}}
    @include('301-service-dashboard.ajax')

    {{-- JavaScript 로직 포함 --}}
    @include('301-service-dashboard.javascript')
</main>
