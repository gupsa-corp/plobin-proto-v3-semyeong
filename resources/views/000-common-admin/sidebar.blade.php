<div class="admin-sidebar w-64 min-h-screen">
    <!-- 로고 -->
    <div class="p-4 border-b border-admin-border">
        <h1 class="text-xl font-bold text-red-400">Plobin</h1>
        <p class="text-sm text-gray-400">관리자 패널</p>
        <div class="mt-2 text-xs text-green-400">● 시스템 정상</div>
    </div>

    <!-- 네비게이션 메뉴 -->
    <nav class="mt-4">
        <div class="px-2 space-y-1">
            <!-- 대시보드 -->
            <a href="/admin" class="admin-nav-item {{ request()->is('admin') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                대시보드
            </a>

            <!-- 사용자 관리 -->
            <a href="/admin/users" class="admin-nav-item {{ request()->is('admin/users*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
                사용자 관리
                <span class="ml-auto bg-blue-600 text-white text-xs px-2 py-1 rounded-full">142</span>
            </a>

            <!-- 컨텐츠 관리 -->
            <a href="/admin/content" class="admin-nav-item {{ request()->is('admin/content*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                컨텐츠 관리
            </a>

            <!-- 보고서 -->
            <a href="/admin/reports" class="admin-nav-item {{ request()->is('admin/reports*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                </svg>
                보고서
            </a>

            <!-- 보안 -->
            <a href="/admin/security" class="admin-nav-item {{ request()->is('admin/security*') ? 'active' : '' }}">
                <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                보안
                <span class="ml-auto w-2 h-2 bg-yellow-400 rounded-full"></span>
            </a>
        </div>

        <!-- 시스템 관리 섹션 -->
        <div class="mt-6 pt-6">
            <div class="px-4">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">시스템</p>
            </div>
            <div class="mt-2 px-2 space-y-1">
                <!-- 시스템 설정 -->
                <a href="/admin/system" class="admin-nav-item {{ request()->is('admin/system*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    시스템 설정
                </a>

                <!-- 백업 -->
                <a href="/admin/backup" class="admin-nav-item {{ request()->is('admin/backup*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                    </svg>
                    백업 관리
                </a>

                <!-- 로그 -->
                <a href="/admin/logs" class="admin-nav-item {{ request()->is('admin/logs*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    시스템 로그
                </a>

                <!-- 모니터링 -->
                <a href="/admin/monitoring" class="admin-nav-item {{ request()->is('admin/monitoring*') ? 'active' : '' }}">
                    <svg class="mr-3 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    모니터링
                    <span class="ml-auto w-2 h-2 bg-green-400 rounded-full"></span>
                </a>
            </div>
        </div>
    </nav>

    <!-- 하단 시스템 정보 -->
    <div class="absolute bottom-4 left-4 right-4">
        <div class="p-3 bg-gray-800 rounded-lg border border-gray-600">
            <div class="text-xs space-y-1">
                <div class="flex justify-between text-gray-400">
                    <span>CPU</span>
                    <span class="text-green-400">23%</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>메모리</span>
                    <span class="text-blue-400">67%</span>
                </div>
                <div class="flex justify-between text-gray-400">
                    <span>디스크</span>
                    <span class="text-yellow-400">45%</span>
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-600">
                <p class="text-xs text-gray-500">v1.0.0 Admin</p>
            </div>
        </div>
    </div>
</div>