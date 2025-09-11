<div class="space-y-3">
    <div class="flex items-center p-4 border border-gray-200 rounded-lg">
        <input
            type="radio"
            id="custom_screen_none"
            name="custom_screen"
            value=""
            class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
            x-model="selectedCustomScreen"
            {{ empty($currentCustomScreenId) ? 'checked' : '' }}
        >
        <label for="custom_screen_none" class="ml-3 flex-1">
            <div class="font-medium text-gray-900">커스텀 화면 사용 안함</div>
            <div class="text-sm text-gray-500">기본 페이지 레이아웃을 사용합니다.</div>
        </label>
    </div>

    <!-- 로딩 상태 -->
    <div x-show="loading" class="flex items-center p-4 border border-blue-200 rounded-lg bg-blue-50">
        <div class="flex-shrink-0">
            <svg class="animate-spin w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <div class="ml-3">
            <div class="text-sm font-medium text-blue-800">화면 목록을 로드하고 있습니다...</div>
            <div class="text-sm text-blue-600">잠시만 기다려주세요.</div>
        </div>
    </div>

    <!-- 에러 상태 -->
    <div x-show="error" class="flex items-center p-4 border border-red-200 rounded-lg bg-red-50">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <div class="text-sm font-medium text-red-800">화면 목록을 로드할 수 없습니다</div>
            <div class="text-sm text-red-600" x-text="error"></div>
        </div>
    </div>

    <!-- 커스텀 화면이 없을 때 오류 메시지 -->
    <div x-show="!loading && !error && customScreens.length === 0" class="flex items-center p-4 border border-yellow-200 rounded-lg bg-yellow-50">
        <div class="flex-shrink-0">
            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <div class="text-sm font-medium text-yellow-800">사용 가능한 화면이 없습니다</div>
            <div class="text-sm text-yellow-600">선택된 샌드박스에 HTML 또는 PHP 화면 파일이 없습니다.</div>
        </div>
    </div>

    <!-- 사용 가능한 커스텀 화면들 -->
    <template x-for="screen in customScreens" :key="screen.id">
        <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
            <input
                type="radio"
                :id="'custom_screen_' + screen.id"
                name="custom_screen"
                :value="screen.id"
                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                x-model="selectedCustomScreen"
            >
            <label :for="'custom_screen_' + screen.id" class="ml-3 flex-1">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900" x-text="screen.title"></div>
                        <div class="text-sm text-gray-500" x-text="screen.description"></div>
                        <div class="flex items-center space-x-3 text-xs text-gray-400 mt-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium"
                                  :class="{
                                      'bg-blue-100 text-blue-800': screen.type === 'html',
                                      'bg-purple-100 text-purple-800': screen.type === 'php'
                                  }">
                                <span x-text="screen.type.toUpperCase()"></span>
                            </span>
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5v4M16 5v4"></path>
                                </svg>
                                <span x-text="screen.created_at"></span>
                            </span>
                            <span class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span x-text="screen.size"></span>
                            </span>
                            <span x-show="screen.directory !== '/'" class="flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5l4 0l0 4M16 5l0 4l-4 0"></path>
                                </svg>
                                <span x-text="screen.directory"></span>
                            </span>
                        </div>
                    </div>
                    <!-- 미리보기 버튼 -->
                    <div class="ml-4">
                        <button
                            type="button"
                            @click="previewScreen(screen.id)"
                            class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            미리보기
                        </button>
                    </div>
                </div>
            </label>
        </div>
    </template>
</div>