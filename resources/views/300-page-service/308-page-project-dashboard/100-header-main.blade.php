<!-- 프로젝트 대시보드 헤더 -->
<header class="bg-white shadow-sm border-b fixed top-0 left-0 right-0 z-50 h-[94px]">
    <div class="flex items-center justify-between h-full px-6">
        <!-- 좌측: 로고 & 프로젝트 정보 -->
        <div class="flex items-center gap-6">
            <!-- 로고/홈 링크 -->
            <div class="flex items-center gap-3">
                <a href="/dashboard" class="text-2xl font-bold text-green-600 hover:text-green-700">
                    Plobin
                </a>
                <span class="text-gray-400">|</span>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" viewBox="0 0 16 16" fill="none">
                        <path d="M2 3C2 2.44772 2.44772 2 3 2H6.58579C6.851 2 7.10536 2.10536 7.29289 2.29289L8.41421 3.41421C8.60174 3.60174 8.85609 3.70711 9.12132 3.70711H13C13.5523 3.70711 14 4.15482 14 4.70711V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V3Z" stroke="currentColor" stroke-width="1.5"/>
                    </svg>
                    <span class="text-lg font-medium text-gray-700">프로젝트명</span>
                </div>
            </div>
        </div>

        <!-- 중앙: 현재 페이지 정보 -->
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500" x-text="currentPage.breadcrumb">대시보드 홈 > 페이지 1</span>
        </div>

        <!-- 우측: 액션 버튼들 -->
        <div class="flex items-center gap-3">
            <!-- 프로젝트 설정 -->
            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                <svg class="w-5 h-5" viewBox="0 0 16 16" fill="none">
                    <path d="M8 10C9.10457 10 10 9.10457 10 8C10 6.89543 9.10457 6 8 6C6.89543 6 6 6.89543 6 8C6 9.10457 6.89543 10 8 10Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M13.5 8C13.5 8.27614 13.2761 8.5 13 8.5C12.7239 8.5 12.5 8.27614 12.5 8C12.5 7.72386 12.7239 7.5 13 7.5C13.2761 7.5 13.5 7.72386 13.5 8Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M3.5 8C3.5 8.27614 3.27614 8.5 3 8.5C2.72386 8.5 2.5 8.27614 2.5 8C2.5 7.72386 2.72386 7.5 3 7.5C3.27614 7.5 3.5 7.72386 3.5 8Z" stroke="currentColor" stroke-width="1.5"/>
                </svg>
            </button>

            <!-- 알림 -->
            <button class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg relative">
                <svg class="w-5 h-5" viewBox="0 0 16 16" fill="none">
                    <path d="M8 2.5C6.067 2.5 4.5 4.067 4.5 6V8.879C4.5 9.23 4.368 9.568 4.133 9.82L3.5 10.5H12.5L11.867 9.82C11.632 9.568 11.5 9.23 11.5 8.879V6C11.5 4.067 9.933 2.5 8 2.5Z" stroke="currentColor" stroke-width="1.5"/>
                    <path d="M6.5 11.5C6.5 12.6046 7.39543 13.5 8.5 13.5C9.60457 13.5 10.5 12.6046 10.5 11.5" stroke="currentColor" stroke-width="1.5"/>
                </svg>
                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full text-xs flex items-center justify-center text-white">3</span>
            </button>

            <!-- 프로필 -->
            <button class="flex items-center gap-2 p-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <span class="text-sm font-medium text-green-600">U</span>
                </div>
                <span class="text-sm font-medium">사용자명</span>
            </button>
        </div>
    </div>
</header>