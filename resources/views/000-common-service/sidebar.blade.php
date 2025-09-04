<div class="service-sidebar w-64 min-h-screen">
    <!-- 로고 -->
    <div class="p-4 border-b border-gray-200">
        <h1 class="text-xl font-bold text-primary-600">Plobin</h1>
        <p class="text-sm text-gray-500">서비스 대시보드</p>
    </div>

    <!-- 네비게이션 메뉴 -->
    <nav class="mt-4">
        <div class="px-2 space-y-1">
            <!-- 대시보드 -->
            <a href="/dashboard" class="service-nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                </svg>
                대시보드
            </a>

            <!-- 프로젝트 -->
            <a href="/projects" class="service-nav-item {{ request()->is('projects*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                프로젝트
            </a>

            <!-- 팀 -->
            <a href="/team" class="service-nav-item {{ request()->is('team*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                팀
            </a>

            <!-- 보고서 -->
            <a href="/reports" class="service-nav-item {{ request()->is('reports*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                보고서
            </a>
        </div>

        <!-- 구분선 -->
        <div class="mt-6 pt-6">
            <div class="px-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">계정</p>
            </div>
            <div class="mt-2 px-2 space-y-1">
                <!-- 프로필 -->
                <a href="/profile" class="service-nav-item {{ request()->is('profile*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    프로필
                </a>

                <!-- 설정 -->
                <a href="/settings" class="service-nav-item {{ request()->is('settings*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    설정
                </a>

                <!-- 도움말 -->
                <a href="/help" class="service-nav-item {{ request()->is('help*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    도움말
                </a>
            </div>
        </div>
    </nav>

    <!-- 하단 정보 -->
    <div class="absolute bottom-4 left-4 right-4">
        <div class="p-3 bg-gray-50 rounded-lg">
            <p class="text-xs text-gray-600">버전 1.0.0</p>
            <p class="text-xs text-gray-500">© 2024 Plobin</p>
        </div>
    </div>
</div>