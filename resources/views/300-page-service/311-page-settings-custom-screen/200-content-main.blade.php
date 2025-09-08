<div class="px-6 py-6" x-data="{ sandboxSelected: false }">
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

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

            <!-- 샌드박스 선택 시 커스텀 화면 설정 폼 (구현필요) -->
            <div x-show="sandboxSelected" style="display: none;">
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            커스텀 화면 선택
                        </label>
                        
                        <div class="text-center py-8 text-gray-500">
                            구현필요
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