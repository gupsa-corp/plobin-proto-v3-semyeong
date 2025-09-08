<div class="px-6 py-6">
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

    <!-- 배포 상태 변경 콘텐츠 -->
    <div class="bg-white rounded-lg border border-gray-200">
        <!-- 헤더 -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">배포 상태 관리</h2>
                    <p class="text-sm text-gray-500 mt-1">페이지의 배포 상태를 변경하고 관리할 수 있습니다.</p>
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
            <!-- 현재 배포 상태 -->
            <div class="mb-6">
                <h3 class="text-sm font-medium text-gray-700 mb-3">현재 상태</h3>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                    배포됨 (Published)
                </div>
                <p class="text-sm text-gray-500 mt-2">구현필요</p>
            </div>

            <form action="#" method="POST" class="space-y-6">
                @csrf
                
                <!-- 배포 상태 선택 -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        배포 상태 변경
                    </label>
                    
                    <div class="space-y-3">
                        <!-- 초안 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="status_draft" 
                                name="deployment_status" 
                                value="draft" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <label for="status_draft" class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    <div class="font-medium text-gray-900">초안 (Draft)</div>
                                </div>
                                <div class="text-sm text-gray-500">페이지가 비공개 상태로 저장됩니다.</div>
                            </label>
                        </div>

                        <!-- 검토 중 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="status_review" 
                                name="deployment_status" 
                                value="review" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <label for="status_review" class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-yellow-400 rounded-full mr-2"></span>
                                    <div class="font-medium text-gray-900">검토 중 (Under Review)</div>
                                </div>
                                <div class="text-sm text-gray-500">페이지가 검토 대기 상태입니다.</div>
                            </label>
                        </div>

                        <!-- 배포됨 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="status_published" 
                                name="deployment_status" 
                                value="published" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                                checked
                            >
                            <label for="status_published" class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                    <div class="font-medium text-gray-900">배포됨 (Published)</div>
                                </div>
                                <div class="text-sm text-gray-500">페이지가 공개되어 접근 가능합니다.</div>
                            </label>
                        </div>

                        <!-- 아카이브됨 -->
                        <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                            <input 
                                type="radio" 
                                id="status_archived" 
                                name="deployment_status" 
                                value="archived" 
                                class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                            >
                            <label for="status_archived" class="ml-3 flex-1">
                                <div class="flex items-center">
                                    <span class="w-2 h-2 bg-gray-600 rounded-full mr-2"></span>
                                    <div class="font-medium text-gray-900">아카이브됨 (Archived)</div>
                                </div>
                                <div class="text-sm text-gray-500">페이지가 보관되어 비활성화 상태입니다.</div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- 변경 사유 -->
                <div>
                    <label for="change_reason" class="block text-sm font-medium text-gray-700 mb-2">
                        변경 사유 (선택사항)
                    </label>
                    <textarea 
                        id="change_reason"
                        name="change_reason"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="배포 상태 변경 사유를 입력하세요"
                    ></textarea>
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
                        상태 변경
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>