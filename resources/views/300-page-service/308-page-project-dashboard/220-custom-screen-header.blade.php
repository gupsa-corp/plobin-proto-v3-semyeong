<!-- 커스텀 화면 헤더 컴포넌트 -->
<div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
                <span class="text-sm font-medium text-blue-800">
                    {{ ucfirst($sandboxName) }} Sandbox
                </span>
            </div>

            <!-- 커스텀 화면 선택 드롭다운 -->
            <div class="relative" x-data="{
                screenOpen: false,
                customScreens: [],
                loading: false,
                error: null,

                getCurrentScreenId() {
                    const urlParams = new URLSearchParams(window.location.search);
                    return urlParams.get('screen') || '';
                },

                getCurrentScreenTitle() {
                    const currentId = this.getCurrentScreenId();
                    if (!currentId) return '{{ $customScreen['title'] ?? '이름 없음' }}';
                    
                    const currentScreen = this.customScreens.find(screen => screen.id === currentId);
                    return currentScreen ? currentScreen.title : '{{ $customScreen['title'] ?? '이름 없음' }}';
                },

                async loadCustomScreens() {
                    if (this.customScreens.length > 0) return; // 이미 로드됨

                    this.loading = true;
                    this.error = null;

                    try {
                        // sandboxName에서 'storage-sandbox-' 접두사 제거
                        let sandboxName = '{{ $sandboxName }}';
                        if (sandboxName.startsWith('storage-sandbox-')) {
                            sandboxName = sandboxName.replace('storage-sandbox-', '');
                        }

                        const response = await fetch(`/api/sandbox/screens?sandbox_name=${sandboxName}`);
                        if (!response.ok) throw new Error('Failed to fetch screens');

                        const data = await response.json();
                        this.customScreens = data.screens || [];
                    } catch (error) {
                        console.error('커스텀 화면 로드 실패:', error);
                        this.error = '화면 목록을 불러올 수 없습니다.';
                        this.customScreens = [];
                    } finally {
                        this.loading = false;
                    }
                },

                async openDropdown() {
                    this.screenOpen = true;
                    let sandboxName = '{{ $sandboxName }}';
                    if (sandboxName) {
                        await this.loadCustomScreens();
                    }
                }
             }">
                <div class="flex items-center">
                    <span class="text-sm text-blue-600 mr-2">커스텀 화면:</span>
                    <button @click="openDropdown()"
                            class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-800 text-sm font-medium rounded-md transition-colors duration-200"
                            x-text="getCurrentScreenTitle()">
                    </button>
                </div>

                <div x-show="screenOpen"
                     @click.away="screenOpen = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute left-0 mt-2 w-72 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                            사용 가능한 화면
                        </div>

                        <!-- 커스텀 화면 사용 안함 옵션 -->
                        <button onclick="changeCustomScreen('', '기본 페이지', event)"
                                class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ empty($customScreen) ? 'bg-blue-50 text-blue-700' : '' }}">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                            </svg>
                            <div>
                                <div class="font-medium">기본 페이지</div>
                                <div class="text-xs text-gray-500">커스텀 화면 사용 안함</div>
                            </div>
                        </button>

                        <!-- 구분선 -->
                        <hr class="my-2 border-gray-200">

                        <!-- 로딩 상태 -->
                        <div x-show="loading" class="flex items-center px-4 py-3 text-sm text-gray-500">
                            <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            화면 목록을 불러오고 있습니다...
                        </div>

                        <!-- 에러 상태 -->
                        <div x-show="error" class="flex items-center px-4 py-3 text-sm text-red-600">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <span x-text="error"></span>
                        </div>

                        <!-- 커스텀 화면이 없을 때 -->
                        <div x-show="!loading && !error && customScreens.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                            사용 가능한 커스텀 화면이 없습니다.
                        </div>

                        <!-- 동적으로 로드된 커스텀 화면들 -->
                        <template x-for="screen in customScreens" :key="screen.id">
                            <button
                                @click="changeCustomScreenDynamic(screen.id, screen.title, $event)"
                                class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                :class="{ 'bg-blue-50 text-blue-700': screen.id === getCurrentScreenId() }">

                                <!-- 화면 타입별 아이콘 -->
                                <div class="w-4 h-4 mr-3 flex-shrink-0">
                                    <span class="inline-flex items-center justify-center w-4 h-4 text-xs font-medium rounded-full"
                                          :class="{
                                              'bg-blue-100 text-blue-800': screen.type === 'html',
                                              'bg-purple-100 text-purple-800': screen.type === 'php',
                                              'bg-green-100 text-green-800': screen.type === 'template'
                                          }">
                                        <span x-text="screen.type.charAt(0).toUpperCase()"></span>
                                    </span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="font-medium truncate" x-text="screen.title"></div>
                                    <div class="text-xs text-gray-500 flex items-center space-x-2 mt-1">
                                        <span x-text="screen.description || 'SCREEN'"></span>
                                        <span>•</span>
                                        <span x-text="screen.modified_at"></span>
                                    </div>
                                </div>

                                <!-- 현재 활성화된 화면 표시 -->
                                <div x-show="screen.id === getCurrentScreenId()" class="ml-2 text-blue-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>

                                <!-- 미리보기 버튼 -->
                                <button
                                    @click.stop="previewScreen(screen.id)"
                                    class="ml-2 px-2 py-1 text-xs text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition-colors duration-200"
                                    title="미리보기">
                                    미리보기
                                </button>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <!-- 페이지 설정 드롭다운 -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                        class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                    </svg>
                    페이지 설정
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                    <div class="py-1">
                        <a href="{{ route('project.dashboard.page.settings', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            일반 설정
                        </a>
                        <a href="{{ route('project.dashboard.page.settings.name', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            페이지 이름
                        </a>
                        <a href="{{ route('project.dashboard.page.settings.permissions', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            권한 관리
                        </a>
                        <hr class="my-1 border-gray-200">
                        <a href="{{ route('project.dashboard.page.settings.custom-screen', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            커스텀 화면 설정
                        </a>
                        <a href="{{ route('project.dashboard.page.settings.deployment', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                            배포 설정
                        </a>
                        <hr class="my-1 border-gray-200">
                        <a href="{{ route('project.dashboard.page.settings.delete', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}"
                           class="flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                            <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            페이지 삭제
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// CSRF 토큰 가져오기
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
}

// 동적 커스텀 화면 변경 함수
async function changeCustomScreenDynamic(screenId, screenTitle, event) {
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;

    try {
        button.innerHTML = '<div class="flex items-center"><div class="w-4 h-4 animate-spin border-2 border-blue-500 border-t-transparent rounded-full mr-2"></div>적용 중...</div>';
        button.disabled = true;

        const response = await fetch(`/api/projects/{{ request()->route('projectId') }}/pages/{{ request()->route('pageId') }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                custom_screen_settings: {
                    screen_id: screenId,
                    screen_title: screenTitle
                }
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();

        showNotification('커스텀 화면이 변경되었습니다.', 'success');

        // 드롭다운 닫기 (안전한 Alpine.js 접근)
        const dropdownElement = button.closest('[x-data]');
        if (dropdownElement && dropdownElement.__x && dropdownElement.__x.$data) {
            dropdownElement.__x.$data.screenOpen = false;
        }

        // 현재 선택된 화면 제목 업데이트
        const currentScreenButton = button.closest('[x-data]').querySelector('button');
        const titleSpan = currentScreenButton.childNodes[0];
        titleSpan.textContent = screenTitle;

        // 선택된 화면을 현재 페이지에 바로 적용
        applyCustomScreen(screenId);

    } catch (error) {
        console.error('커스텀 화면 변경 중 오류:', error);
        showNotification('커스텀 화면 변경에 실패했습니다.', 'error');

        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// 미리보기 함수
function previewScreen(screenId) {
    if (window.customScreensData && window.currentSandboxName) {
        const screen = window.customScreensData.find(s => s.id == screenId);
        if (screen) {
            // 모든 스크린은 폴더명으로 접근
            const folderName = screen.name;
            const previewUrl = `/sandbox/${window.currentSandboxName}/${folderName}`;
            window.open(previewUrl, 'screen-preview', 'width=1200,height=800,scrollbars=yes,resizable=yes');
        }
    }
}

// 기존 커스텀 화면 변경 함수 (하위호환성)
async function changeCustomScreen(screenType, screenTitle, event) {
    // 버튼과 원본 콘텐츠를 함수 레벨에서 선언
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;

    try {
        // 로딩 상태 표시
        button.innerHTML = '<div class="flex items-center"><div class="w-4 h-4 animate-spin border-2 border-blue-500 border-t-transparent rounded-full mr-2"></div>적용 중...</div>';
        button.disabled = true;

        // API 호출
        const response = await fetch(`/api/projects/{{ request()->route('projectId') }}/pages/{{ request()->route('pageId') }}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                custom_screen_settings: {
                    screen_name: screenType,
                    screen_title: screenTitle
                }
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        const data = await response.json();

        // 성공 메시지 표시
        showNotification('커스텀 화면이 변경되었습니다.', 'success');

        // 드롭다운 닫기 (안전한 Alpine.js 접근)
        const dropdownElement = button.closest('[x-data]');
        if (dropdownElement && dropdownElement.__x && dropdownElement.__x.$data) {
            dropdownElement.__x.$data.screenOpen = false;
        }

        // 현재 선택된 화면 제목 업데이트
        const currentScreenButton = button.closest('[x-data]').querySelector('button[\\@click="screenOpen = !screenOpen"]');
        currentScreenButton.innerHTML = `
            ${screenTitle}
            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        `;

        // 선택된 화면을 현재 페이지에 바로 적용
        applyCustomScreen(screenType);

    } catch (error) {
        console.error('커스텀 화면 변경 중 오류:', error);
        showNotification('커스텀 화면 변경에 실패했습니다.', 'error');

        // 버튼 상태 복원
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

// 알림 표시 함수
function showNotification(message, type = 'info') {
    // 기존 알림 제거
    const existingNotification = document.querySelector('.custom-notification');
    if (existingNotification) {
        existingNotification.remove();
    }

    // 알림 생성
    const notification = document.createElement('div');
    notification.className = `custom-notification fixed top-4 right-4 z-[9999] px-4 py-3 rounded-lg shadow-lg max-w-sm ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        'bg-blue-500 text-white'
    }`;

    notification.innerHTML = `
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                ${type === 'success' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>' :
                    type === 'error' ?
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' :
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                }
            </svg>
            <span>${message}</span>
        </div>
    `;

    document.body.appendChild(notification);

    // 3초 후 자동 제거
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// 커스텀 화면 적용 함수 (페이지 새로고침 없이)
function applyCustomScreen(screenType) {
    // URL 파라미터 업데이트
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('screen', screenType);
    window.history.pushState({}, '', currentUrl);

    // 메인 콘텐츠 영역 찾기
    const mainContent = document.querySelector('.main-content, #main-content, [data-content="main"]') ||
                       document.querySelector('main') ||
                       document.querySelector('.container');

    if (mainContent) {
        // 로딩 상태 표시
        const loadingHtml = `
            <div class="flex items-center justify-center p-8">
                <div class="w-8 h-8 animate-spin border-4 border-blue-500 border-t-transparent rounded-full mr-3"></div>
                <span class="text-gray-600">새로운 화면을 로드하고 있습니다...</span>
            </div>
        `;
        mainContent.innerHTML = loadingHtml;

        // 새로운 화면 로드
        fetch(currentUrl.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            // 새로운 HTML에서 메인 콘텐츠 부분만 추출하여 교체
            const parser = new DOMParser();
            const newDoc = parser.parseFromString(html, 'text/html');
            const newMainContent = newDoc.querySelector('.main-content, #main-content, [data-content="main"]') ||
                                  newDoc.querySelector('main') ||
                                  newDoc.querySelector('.container');

            if (newMainContent) {
                mainContent.innerHTML = newMainContent.innerHTML;
            } else {
                // 전체 페이지 새로고침 (fallback)
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('화면 로드 중 오류:', error);
            // 에러 발생 시 전체 페이지 새로고침
            window.location.reload();
        });
    } else {
        // 메인 콘텐츠를 찾을 수 없는 경우 전체 페이지 새로고침
        window.location.reload();
    }
}

// 기존 함수 (호환성 유지)
function changeScreen(screenType) {
    applyCustomScreen(screenType);
    return false;
}
</script>
