<script>
// Initialize custom screens data safely before Alpine.js loads
window.customScreensData = [
    {
        "id": "1",
        "title": "조직 목록",
        "description": "조직 관리를 위한 목록 화면입니다.",
        "type": "list",
        "created_at": "2024-01-01"
    },
    {
        "id": "2", 
        "title": "조직 목록 (복사본)",
        "description": "조직 관리를 위한 목록 화면입니다.",
        "type": "list",
        "created_at": "2024-01-02"
    },
    {
        "id": "3",
        "title": "프로젝트 목록", 
        "description": "프로젝트 목록을 보여주는 화면입니다",
        "type": "list",
        "created_at": "2024-01-03"
    }
];
</script>

<div class="px-6 py-6" x-data="{
    sandboxSelected: true,
    selectedCustomScreen: '{{ $currentCustomScreenSettings['screen_id'] ?? '' }}',
    customScreens: window.customScreensData || []
}"
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
                            
                            <!-- 커스텀 화면이 없을 때 오류 메시지 -->
                            <div x-show="customScreens.length === 0" class="flex items-center p-4 border border-red-200 rounded-lg bg-red-50">
                                <div class="flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-red-800">사용 가능한 커스텀 화면이 없습니다</div>
                                    <div class="text-sm text-red-600">샌드박스에서 커스텀 화면을 먼저 생성해주세요.</div>
                                </div>
                            </div>
                            
                            <!-- 사용 가능한 커스텀 화면들 -->
                            <template x-for="screen in customScreens" :key="screen.id">
                                <div class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
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
                                                <div class="flex items-center space-x-3 text-xs text-gray-400 mt-1">
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full" 
                                                          x-text="screen.type.charAt(0).toUpperCase() + screen.type.slice(1)"></span>
                                                    <span x-text="screen.created_at"></span>
                                                </div>
                                            </div>
                                            <!-- 미리보기 버튼 -->
                                            <div class="ml-4">
                                                <button 
                                                    type="button"
                                                    @click="window.open('/sandbox/custom-screen/preview/' + screen.id, 'preview', 'width=1200,height=800')"
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
                            
                            <!-- 샌드박스로 이동해서 새 화면 만들기 -->
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