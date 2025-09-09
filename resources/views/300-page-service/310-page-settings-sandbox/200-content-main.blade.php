<div class="px-6 py-6">
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

    <!-- 샌드박스 선택 콘텐츠 -->
    <div class="bg-white rounded-lg border border-gray-200">
        <!-- 헤더 -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">샌드박스 선택</h2>
                    <p class="text-sm text-gray-500 mt-1">이 페이지에서 사용할 샌드박스를 선택할 수 있습니다.</p>
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
            <form action="{{ route('project.dashboard.page.settings.sandbox.post', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => request()->route('pageId')]) }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- 샌드박스 목록 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        사용 가능한 샌드박스
                    </label>
                    
                    <div class="space-y-3">
                        <!-- 샌드박스 옵션들 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="sandbox_none" 
                                name="sandbox" 
                                value="" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                {{ (empty($currentSandboxType)) ? 'checked' : '' }}
                            >
                            <label for="sandbox_none" class="ml-3 flex-1">
                                <div class="font-medium text-gray-900">샌드박스 사용 안함</div>
                                <div class="text-sm text-gray-500">기본 페이지 콘텐츠만 표시합니다.</div>
                            </label>
                        </div>
                        
                        <!-- 샌드박스 템플릿 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="sandbox_template" 
                                name="sandbox" 
                                value="template" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                {{ ($currentSandboxType === 'template') ? 'checked' : '' }}
                            >
                            <label for="sandbox_template" class="ml-3 flex-1">
                                <div class="font-medium text-gray-900">템플릿 샌드박스</div>
                                <div class="text-sm text-gray-500">기본 템플릿이 포함된 샌드박스 환경입니다.</div>
                            </label>
                        </div>

                        <!-- 샌드박스 1 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="sandbox_1" 
                                name="sandbox" 
                                value="1" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                {{ ($currentSandboxType === '1') ? 'checked' : '' }}
                            >
                            <label for="sandbox_1" class="ml-3 flex-1">
                                <div class="font-medium text-gray-900">개발용 샌드박스 1</div>
                                <div class="text-sm text-gray-500">개발 및 테스트를 위한 샌드박스 환경입니다.</div>
                            </label>
                        </div>

                        <!-- 사용자 정의 샌드박스 생성 옵션 -->
                        <div class="flex items-center p-4 border border-dashed border-gray-300 rounded-lg">
                            <div class="flex-1">
                                <div class="font-medium text-gray-700">새 샌드박스 생성</div>
                                <div class="text-sm text-gray-500">새로운 샌드박스 환경을 생성하여 사용할 수 있습니다.</div>
                            </div>
                            <a href="/sandbox/storage-manager" 
                               class="ml-3 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                생성
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 저장 버튼 -->
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