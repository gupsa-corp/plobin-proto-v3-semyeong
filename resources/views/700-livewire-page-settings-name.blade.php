<div class="bg-white">
    <!-- 페이지 헤더 -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-medium text-gray-900">페이지 이름 변경</h2>
                <p class="mt-1 text-sm text-gray-500">
                    "{{ $page->title }}" 페이지의 이름을 변경할 수 있습니다.
                </p>
            </div>
            <!-- 뒤로가기 버튼 -->
            <a href="{{ route('project.dashboard.page', ['id' => request()->route('id'), 'projectId' => request()->route('projectId'), 'pageId' => $pageId]) }}" 
               class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                페이지로 이동
            </a>
        </div>
    </div>

    <!-- 성공 메시지 -->
    @if (session()->has('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- 폼 영역 -->
    <div class="px-6 py-6">
        <form wire:submit.prevent="updateTitle" class="space-y-6">
            <!-- 페이지 제목 입력 -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">페이지 제목</label>
                <div class="mt-1">
                    <input type="text" 
                           wire:model.defer="title" 
                           id="title"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('title') border-red-300 @enderror"
                           placeholder="페이지 제목을 입력하세요">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <p class="mt-2 text-sm text-gray-500">
                    이 이름은 사이드바 네비게이션과 페이지 상단에 표시됩니다.
                </p>
            </div>

            <!-- 프로젝트 정보 -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-sm font-medium text-gray-900 mb-2">페이지 정보</h4>
                <dl class="grid grid-cols-1 gap-x-4 gap-y-2 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">소속 프로젝트</dt>
                        <dd class="text-sm text-gray-900">{{ $page->project->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">현재 제목</dt>
                        <dd class="text-sm text-gray-900">{{ $page->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">생성일</dt>
                        <dd class="text-sm text-gray-900">{{ $page->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">최근 수정</dt>
                        <dd class="text-sm text-gray-900">{{ $page->updated_at->format('Y-m-d H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- 버튼 영역 -->
            <div class="flex items-center justify-end space-x-3">
                <button type="button" 
                        onclick="window.history.back()" 
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    취소
                </button>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <span wire:loading.remove>변경사항 저장</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        저장 중...
                    </span>
                </button>
            </div>
        </form>
    </div>

    <!-- 주의사항 -->
    <div class="px-6 pb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h4 class="text-sm font-medium text-yellow-800">주의사항</h4>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>페이지 제목 변경 시 모든 변경 내역은 로그에 기록됩니다.</li>
                            <li>제목은 사이드바 네비게이션에 즉시 반영됩니다.</li>
                            <li>페이지 URL은 변경되지 않습니다.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
