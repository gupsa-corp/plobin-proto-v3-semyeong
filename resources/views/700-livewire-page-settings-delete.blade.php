<div class="px-6 py-6">
    <!-- 페이지 삭제 콘텐츠 -->
    <div class="bg-white rounded-lg border border-red-200">
        <!-- 헤더 -->
        <div class="px-6 py-4 border-b border-red-200 bg-red-50">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-lg font-semibold text-red-900">위험 영역</h2>
                    <p class="text-sm text-red-700 mt-1">이 작업은 되돌릴 수 없습니다. 신중하게 진행해주세요.</p>
                </div>
                <a href="{{ route('project.dashboard.page', ['id' => $page->project->organization_id, 'projectId' => $page->project_id, 'pageId' => $page->id]) }}" 
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
            @if (session()->has('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                    <div class="flex">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">
                                {{ session('error') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

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
                    <p><span class="font-medium">페이지 이름:</span> {{ $page->title }}</p>
                    <p><span class="font-medium">생성일:</span> {{ $page->created_at->format('Y년 m월 d일') }}</p>
                    <p><span class="font-medium">마지막 수정:</span> {{ $page->updated_at->format('Y년 m월 d일 H:i') }}</p>
                </div>
            </div>

            <!-- 확인 입력 -->
            <form wire:submit="deletePage" class="space-y-4">
                <div>
                    <label for="confirm_delete" class="block text-sm font-medium text-gray-700 mb-2">
                        삭제를 확인하려면 <strong class="text-red-600">"삭제"</strong>를 입력하세요
                    </label>
                    <input 
                        type="text" 
                        id="confirm_delete"
                        wire:model="confirmText"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('confirmText') border-red-300 @enderror"
                        placeholder="삭제"
                    >
                    @error('confirmText')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
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
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 disabled:bg-gray-300 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="deletePage"
                    >
                        <span wire:loading.remove wire:target="deletePage">페이지 삭제</span>
                        <span wire:loading wire:target="deletePage">삭제 중...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>