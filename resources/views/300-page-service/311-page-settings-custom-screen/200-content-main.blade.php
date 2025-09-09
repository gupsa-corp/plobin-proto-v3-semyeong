<script>
// Initialize custom screens data safely before Alpine.js loads
// 백엔드에서 전달받은 실제 커스텀 화면 데이터 사용
window.customScreensData = @json($customScreens ?? []);
window.currentSandboxType = @json($currentSandboxType ?? '');
</script>

<div class="px-6 py-6" x-data="customScreenSettingsPage()"
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

    <!-- 알림 메시지 -->
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- 커스텀 화면 선택 콘텐츠 -->
    <div class="bg-white rounded-lg border border-gray-200">
        <!-- 헤더 -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">커스텀 화면 선택</h2>
                    <p class="text-sm text-gray-500 mt-1">샌드박스를 선택한 경우에만 커스텀 화면을 설정할 수 있습니다.</p>
                </div>
                <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    페이지로 이동
                </a>
            </div>
        </div>

        <!-- 콘텐츠 -->
        <div class="p-6">
            <!-- 샌드박스 미선택 시 안내 메시지 -->
            <div x-show="!sandboxSelected" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 18.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">커스텀 화면을 사용할 수 없습니다</h3>
                <p class="mt-1 text-sm text-gray-500">먼저 샌드박스를 선택해야 커스텀 화면을 설정할 수 있습니다.</p>
                <div class="mt-4">
                    <a href="{{ route('project.dashboard.page.settings.sandbox', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        샌드박스 선택하기
                    </a>
                </div>
            </div>

            <!-- 샌드박스 선택 시 커스텀 화면 설정 폼 -->
            <div x-show="sandboxSelected">
                <form action="{{ route('project.dashboard.page.settings.custom-screen.post', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- 커스텀 화면 사용 안함 옵션 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            커스텀 화면 설정
                        </label>

                        <div class="space-y-3">
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                                <input
                                    type="radio"
                                    id="custom_screen_none"
                                    name="custom_screen"
                                    value=""
                                    class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                    x-model="selectedCustomScreen"
                                    {{ empty($currentCustomScreenSettings['screen_id']) ? 'checked' : '' }}
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

                            <!-- 샌드박스로 이동해서 새 화면 만들기 및 템플릿 선택 -->
                            <div class="space-y-3">
                                <!-- 새 화면 만들기 -->
                                <div class="flex items-center p-4 border border-dashed border-gray-300 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-700">새 커스텀 화면 만들기</div>
                                        <div class="text-sm text-gray-500">샌드박스에서 새로운 커스텀 화면을 생성할 수 있습니다.</div>
                                    </div>
                                    <a href="/sandbox/custom-screen-creator" target="_blank"
                                       class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        생성하기
                                    </a>
                                </div>

                                <!-- 템플릿에서 선택하기 -->
                                <div class="flex items-center p-4 border border-dashed border-purple-300 rounded-lg bg-purple-25">
                                    <div class="flex-1">
                                        <div class="font-medium text-purple-700 flex items-center">
                                            🎨 템플릿에서 화면 가져오기
                                        </div>
                                        <div class="text-sm text-purple-600">미리 만들어진 템플릿 화면을 선택하여 빠르게 설정할 수 있습니다.</div>
                                    </div>
                                    <a href="/sandbox/custom-screens" target="_blank"
                                       class="ml-3 inline-flex items-center px-3 py-2 border border-purple-300 shadow-sm text-sm leading-4 font-medium rounded-md text-purple-700 bg-white hover:bg-purple-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"></path>
                                        </svg>
                                        템플릿 선택
                                    </a>
                                </div>

                                <!-- 새로고침 버튼 -->
                                <div class="flex items-center p-3 border border-blue-200 rounded-lg bg-blue-25">
                                    <div class="flex-1">
                                        <div class="font-medium text-blue-700 text-sm">화면을 새로 만들거나 배포했나요?</div>
                                        <div class="text-xs text-blue-600">새로고침하여 최신 화면 목록을 확인하세요.</div>
                                    </div>
                                    <button type="button"
                                            @click="location.reload()"
                                            class="ml-3 inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        새로고침
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 선택된 화면 정보 -->
                    <div x-show="selectedCustomScreen && selectedCustomScreen !== ''" x-transition>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">선택된 커스텀 화면</h4>
                                    <div class="mt-1 text-sm text-blue-700">
                                        <span x-text="customScreens.find(s => s.id == selectedCustomScreen)?.title"></span>
                                        화면이 이 페이지에 적용됩니다.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            취소
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                            저장
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function customScreenSettingsPage() {
    return {
        sandboxSelected: window.currentSandboxType !== '',
        selectedCustomScreen: '{{ $currentCustomScreenSettings['screen_id'] ?? '' }}',
        customScreens: window.customScreensData || [],
        loading: false,
        error: null,

        init() {
            // 샌드박스가 선택된 경우 스크린 목록을 로드
            if (this.sandboxSelected && window.currentSandboxType) {
                this.loadCustomScreens();
            }
        },

        async loadCustomScreens() {
            // 백엔드에서 이미 데이터를 전달받았으므로 API 호출 불필요
            this.customScreens = window.customScreensData || [];
            this.loading = false;
            this.error = null;

            console.log(`${this.customScreens.length}개의 화면을 로드했습니다.`);
        },

        // 스크린 미리보기 함수
        previewScreen(screenId) {
            const screen = this.customScreens.find(s => s.id == screenId);
            if (screen) {
                // template_ 접두사 제거 후 새로운 경로로 변경
                let previewUrl;
                if (screenId.startsWith('template_')) {
                    const templateId = screenId.replace('template_', '');
                    previewUrl = `/sandbox/storage-sandbox-template/${templateId}`;
                } else {
                    previewUrl = `/sandbox/custom-screen/preview/${screenId}`;
                }
                window.open(previewUrl, 'screen-preview', 'width=1200,height=800,scrollbars=yes,resizable=yes');
            }
        }
    }
}
</script>
