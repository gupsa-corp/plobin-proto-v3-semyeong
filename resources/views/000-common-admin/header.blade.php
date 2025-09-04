<header class="admin-header">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- 모바일 메뉴 버튼 -->
            <div class="md:hidden">
                <button id="admin-sidebar-toggle" type="button" class="p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- 시스템 상태 -->
            <div class="flex-1 max-w-xs md:max-w-md mx-4">
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="text-sm text-gray-300">시스템 정상</span>
                    </div>
                    <div class="admin-status success text-xs">
                        <span data-status="server">온라인</span>
                    </div>
                </div>
            </div>

            <!-- 우측 메뉴 -->
            <div class="flex items-center space-x-4">
                <!-- 시스템 모니터링 -->
                <button class="p-2 text-gray-400 hover:text-white relative">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <div class="absolute -top-1 -right-1 w-3 h-3 bg-green-400 rounded-full"></div>
                </button>

                <!-- 알림 -->
                <button class="p-2 text-gray-400 hover:text-white relative">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-yellow-500 rounded-full text-xs text-black flex items-center justify-center">5</span>
                </button>

                <!-- 빠른 액션 -->
                <div class="hidden md:flex items-center space-x-2">
                    <button class="btn-admin btn-admin-primary text-xs px-3 py-1">
                        백업
                    </button>
                    <button class="btn-admin text-xs px-3 py-1 bg-gray-600 text-white hover:bg-gray-500">
                        로그
                    </button>
                </div>

                <!-- 관리자 메뉴 -->
                <div class="relative">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-700">
                        <div class="h-8 w-8 bg-red-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            A
                        </div>
                        <div class="hidden md:block text-left">
                            <div class="text-sm font-medium text-white">관리자</div>
                            <div class="text-xs text-gray-400">Admin User</div>
                        </div>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- 드롭다운 메뉴 -->
                    <div class="absolute right-0 mt-2 w-48 admin-card rounded-md shadow-lg py-1 z-50 hidden">
                        <a href="/admin/profile" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">프로필</a>
                        <a href="/admin/settings" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">시스템 설정</a>
                        <a href="/admin/logs" class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">시스템 로그</a>
                        <div class="border-t border-gray-600"></div>
                        <a href="/admin/logout" class="block px-4 py-2 text-sm text-red-400 hover:bg-gray-600">로그아웃</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>