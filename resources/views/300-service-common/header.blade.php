<header class="service-header">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <!-- 모바일 메뉴 버튼 -->
            <div class="md:hidden">
                <button id="sidebar-toggle" type="button" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- 검색 -->
            <div class="flex-1 max-w-xs md:max-w-md mx-4">
                <div class="relative">
                    <input type="text" 
                           placeholder="검색..." 
                           class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- 우측 메뉴 -->
            <div class="flex items-center space-x-4">
                <!-- 알림 -->
                <button class="p-2 text-gray-400 hover:text-gray-500 relative">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full text-xs text-white flex items-center justify-center">3</span>
                </button>

                <!-- 사용자 메뉴 -->
                <div class="relative">
                    <button class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
                        <div class="h-8 w-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            U
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">사용자</span>
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    
                    <!-- 드롭다운 메뉴 (JavaScript로 토글) -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 hidden">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">프로필</a>
                        <a href="/settings" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">설정</a>
                        <div class="border-t border-gray-100"></div>
                        <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">로그아웃</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>