<main class="service-main flex-1 p-6">
    {{-- 조직 대시보드 메인 콘텐츠 --}}
    <div id="organizationDashboardContent">
        {{-- 조직 정보 헤더 --}}
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-4">
                {{-- 조직 아바타 --}}
                <div id="orgAvatar" class="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-2xl" style="background-color: #0DC8AF">
                    ?
                </div>
                <div class="flex-1">
                    <h1 id="orgName" class="text-3xl font-bold text-gray-900 mb-2">조직명 로딩중...</h1>
                    <div class="flex items-center text-lg text-gray-500 gap-2">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M8 8V6C8 3.79086 9.79086 2 12 2C14.2091 2 16 3.79086 16 6V8M8 8H16M8 8H6C4.89543 8 4 8.89543 4 10V18C4 19.1046 4.89543 20 6 20H18C19.1046 20 20 19.1046 20 18V10C20 8.89543 19.1046 8 18 8H16"/>
                        </svg>
                        <span id="orgCode">조직코드 로딩중...</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="inviteMemberBtn" class="flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm rounded-lg h-10">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="8.5" cy="7" r="4"/>
                        <line x1="20" y1="8" x2="20" y2="14"/>
                        <line x1="23" y1="11" x2="17" y2="11"/>
                    </svg>
                    멤버 초대
                </button>
                <button id="orgSettingsBtn" class="flex items-center justify-center gap-2 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium text-sm rounded-lg h-10">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="m12 1 1.68 2.35L16 4.5l1.15 2.32L20 8l-1.15 2.32L16 11.5l-2.32 1.15L12 15l-1.68-2.35L8 11.5l-1.15-2.32L4 8l1.15-2.32L8 4.5l2.32-1.15L12 1z"/>
                    </svg>
                    설정
                </button>
            </div>
        </div>

        {{-- 대시보드 통계 카드들 --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- 멤버 수 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-blue-600">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="m22 21-3-3m0 0a5 5 0 1 0-7-7 5 5 0 0 0 7 7z"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900" id="memberCount">-</p>
                        <p class="text-sm text-gray-500">멤버</p>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">+12%</span>
                    <span class="text-gray-500 ml-2">지난 달 대비</span>
                </div>
            </div>

            {{-- 진행중인 프로젝트 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-green-600">
                            <path d="M19 11H5m14 0a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-6a2 2 0 0 1 2-2m14 0V9a2 2 0 0 0-2-2M5 11V9a2 2 0 0 1 2-2m0 0V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900" id="projectCount">-</p>
                        <p class="text-sm text-gray-500">프로젝트</p>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">+3</span>
                    <span class="text-gray-500 ml-2">이번 달 신규</span>
                </div>
            </div>

            {{-- 완료된 작업 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-purple-600">
                            <path d="m9 12 2 2 4-4"/>
                            <path d="M21 12c-.81 0-1.5-.6-1.91-1.23A10 10 0 1 1 12 2c.81 0 1.5.6 1.91 1.23"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900" id="completedTasks">-</p>
                        <p class="text-sm text-gray-500">완료된 작업</p>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">+28%</span>
                    <span class="text-gray-500 ml-2">지난 주 대비</span>
                </div>
            </div>

            {{-- 활성 사용자 --}}
            <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="text-orange-600">
                            <path d="M8 2v4"/>
                            <path d="M16 2v4"/>
                            <rect width="18" height="18" x="3" y="4" rx="2"/>
                            <path d="M3 10h18"/>
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900" id="activeUsers">-</p>
                        <p class="text-sm text-gray-500">활성 사용자</p>
                    </div>
                </div>
                <div class="flex items-center text-sm">
                    <span class="text-green-600 font-medium">+5%</span>
                    <span class="text-gray-500 ml-2">오늘 기준</span>
                </div>
            </div>
        </div>

        {{-- 최근 활동 및 프로젝트 섹션 --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- 최근 활동 --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900">최근 활동</h2>
                </div>
                <div class="p-6">
                    <div id="recentActivities" class="space-y-4">
                        <div class="animate-pulse">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gray-300 rounded-full"></div>
                                <div class="flex-1">
                                    <div class="h-4 bg-gray-300 rounded w-3/4 mb-2"></div>
                                    <div class="h-3 bg-gray-300 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 최근 프로젝트 --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold text-gray-900">최근 프로젝트</h2>
                        <button class="text-teal-500 hover:text-teal-600 font-medium text-sm">모두 보기</button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="recentProjects" class="space-y-4">
                        <div class="animate-pulse">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded"></div>
                                    <div>
                                        <div class="h-4 bg-gray-300 rounded w-24 mb-2"></div>
                                        <div class="h-3 bg-gray-300 rounded w-16"></div>
                                    </div>
                                </div>
                                <div class="h-6 bg-gray-300 rounded-full w-16"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript 로직 포함 --}}
    @include('302-service-organization-dashboard.javascript')
</main>