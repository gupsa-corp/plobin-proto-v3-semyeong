<div class="px-6 py-6">
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

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
            <form action="#" method="POST" class="space-y-6">
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
                                checked
                            >
                            <label for="sandbox_none" class="ml-3 flex-1">
                                <div class="font-medium text-gray-900">샌드박스 사용 안함</div>
                                <div class="text-sm text-gray-500">기본 페이지 콘텐츠만 표시합니다.</div>
                            </label>
                        </div>
                        
                        <!-- 구현필요: 실제 샌드박스 목록 -->
                        <div class="text-center py-8 text-gray-500">
                            구현필요
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