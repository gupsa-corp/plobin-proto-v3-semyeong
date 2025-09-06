<div class="fixed left-0 top-[94px] w-[220px] h-[calc(100vh-94px)] flex flex-col gap-4 p-4 bg-white border-r overflow-y-auto">
    <!-- 프로젝트 페이지 섹션 -->
    <div class="flex flex-col gap-2">
        <!-- 섹션 헤더 -->
        <div class="flex items-center justify-between px-2">
            <span class="text-xs font-medium text-gray-600">프로젝트</span>
            <button class="w-5 h-5 flex items-center justify-center text-gray-400 hover:text-green-600">
                <svg class="w-4 h-4" viewBox="0 0 16 16" fill="none">
                    <path d="M8 1V15M1 8H15" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
            </button>
        </div>

        <!-- 페이지 계층구조 -->
        <div class="flex flex-col gap-1">
            <!-- 1depth - 메인 페이지 -->
            <div class="flex items-center justify-between p-2 bg-white rounded-lg hover:bg-gray-50 cursor-pointer" @click="switchPage('dashboard-home')">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 16 16" fill="none">
                        <path d="M2 4C2 3.44772 2.44772 3 3 3H13C13.5523 3 14 3.44772 14 4V12C14 12.5523 13.5523 13 13 13H3C2.44772 13 2 12.5523 2 12V4Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M6 7H10M6 9H8" stroke="currentColor" stroke-width="1"/>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">페이지</span>
                </div>
                <button class="w-4 h-4 text-gray-400 hover:text-red-500" @click.stop>
                    <svg viewBox="0 0 16 16" fill="none">
                        <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <!-- 2depth - 하위 페이지들 -->
            <div class="ml-4 flex flex-col gap-1">
                <!-- 활성 하위 페이지 -->
                <div class="flex items-center justify-between p-2 bg-green-100 border-l-2 border-green-500 rounded-lg cursor-pointer" @click="switchPage('page-1')">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-600" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span class="text-sm font-medium text-green-700">페이지 1</span>
                    </div>
                    <button class="w-4 h-4 text-green-400 hover:text-red-500" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <!-- 일반 하위 페이지들 -->
                <div class="flex items-center justify-between p-2 bg-white rounded-lg hover:bg-gray-50 cursor-pointer" @click="switchPage('page-2')">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span class="text-sm text-gray-600">페이지 2</span>
                    </div>
                    <button class="w-4 h-4 text-gray-400 hover:text-red-500" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>

                <div class="flex items-center justify-between p-2 bg-white rounded-lg hover:bg-gray-50 cursor-pointer" @click="switchPage('page-3')">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-400" viewBox="0 0 16 16" fill="none">
                            <rect x="2" y="3" width="12" height="10" rx="1" stroke="currentColor" stroke-width="1.5"/>
                            <path d="M5 7H11M5 9H9" stroke="currentColor" stroke-width="1"/>
                        </svg>
                        <span class="text-sm text-gray-600">페이지 3</span>
                    </div>
                    <button class="w-4 h-4 text-gray-400 hover:text-red-500" @click.stop>
                        <svg viewBox="0 0 16 16" fill="none">
                            <path d="M12 4L4 12M4 4L12 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
