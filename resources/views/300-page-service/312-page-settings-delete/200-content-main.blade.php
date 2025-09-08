<div class="px-6 py-6">
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

    <!-- 페이지 삭제 콘텐츠 -->
    <div class="bg-white rounded-lg border border-red-200">
        <!-- 헤더 -->
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold text-red-900">위험 영역</h2>
                    <p class="text-sm text-red-700 mt-1">이 작업은 되돌릴 수 없습니다. 신중하게 진행해주세요.</p>
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
            <!-- 경고 메시지 -->
            <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                <div class="flex">
                    <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">
                            페이지 삭제 주의사항
                        </h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <ul class="list-disc space-y-1 pl-5">
                                <li>페이지를 삭제하면 모든 콘텐츠와 설정이 영구적으로 삭제됩니다.</li>
                                <li>하위 페이지가 있는 경우 먼저 하위 페이지를 삭제해야 합니다.</li>
                                <li>삭제된 페이지는 복구할 수 없습니다.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 페이지 정보 -->
            <div class="mb-6 p-4 bg-gray-50 rounded-md">
                <h3 class="text-sm font-medium text-gray-900 mb-2">삭제할 페이지 정보</h3>
                <div class="text-sm text-gray-600">
                    <p><span class="font-medium">페이지 이름:</span> 구현필요</p>
                    <p><span class="font-medium">생성일:</span> 구현필요</p>
                    <p><span class="font-medium">마지막 수정:</span> 구현필요</p>
                </div>
            </div>

            <!-- 확인 입력 -->
            <form action="#" method="POST" class="space-y-4" x-data="{ confirmText: '', canDelete: false }" 
                  x-init="$watch('confirmText', value => canDelete = value === '삭제')">
                @csrf
                @method('DELETE')
                
                <div>
                    <label for="confirm_delete" class="block text-sm font-medium text-gray-700 mb-2">
                        삭제를 확인하려면 <strong class="text-red-600">"삭제"</strong>를 입력하세요
                    </label>
                    <input 
                        type="text" 
                        id="confirm_delete"
                        name="confirm_delete"
                        x-model="confirmText"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="삭제"
                    >
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button 
                        type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        onclick="history.back()"
                    >
                        취소
                    </button>
                    <button 
                        type="submit"
                        :disabled="!canDelete"
                        :class="canDelete ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-4 py-2 text-sm font-medium text-white rounded-md focus:outline-none focus:ring-2"
                    >
                        페이지 삭제
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>