<!-- 프로젝트 삭제 Livewire 컴포넌트 -->
<div>
    <!-- 오류 메시지 -->
    @if (session()->has('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- 페이지 삭제 필요 경고 -->
    @if($this->getRemainingPagesCount() > 0)
        <div class="rounded-md bg-orange-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-orange-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-orange-800">페이지 삭제 필요</h3>
                    <div class="mt-2 text-sm text-orange-700">
                        <p>프로젝트를 삭제하기 전에 모든 페이지를 먼저 삭제해야 합니다.</p>
                        <p class="mt-1">현재 <strong>{{ $this->getRemainingPagesCount() }}개의 페이지</strong>가 남아있습니다.</p>
                        <div class="mt-3">
                            <a href="{{ route('project.dashboard.project.settings.page-delete', ['id' => $organizationId, 'projectId' => $projectId]) }}" 
                               class="inline-flex items-center px-3 py-2 border border-orange-300 shadow-sm text-sm leading-4 font-medium rounded-md text-orange-700 bg-orange-100 hover:bg-orange-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                페이지 삭제하러 가기
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 남은 페이지 목록 -->
        <div class="rounded-md bg-gray-50 p-4 mb-6">
            <h4 class="text-sm font-medium text-gray-900 mb-3">삭제해야 할 페이지들:</h4>
            <ul class="text-sm text-gray-700 space-y-1">
                @foreach($this->getRemainingPages() as $page)
                    <li class="flex items-center">
                        <svg class="h-4 w-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        {{ $page->title }}
                    </li>
                @endforeach
            </ul>
        </div>
    @else
        <!-- 위험 경고 -->
        <div class="rounded-md bg-red-50 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">위험한 작업</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>이 작업은 소프트 삭제로 처리되어 복구가 가능하지만, 일반 사용자에게는 더 이상 표시되지 않습니다.</p>
                        <p class="mt-1 font-semibold text-green-700">✓ 모든 페이지가 삭제되어 프로젝트 삭제가 가능합니다.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 프로젝트 삭제 폼 -->
    <div class="bg-white shadow rounded-lg border-2 border-red-200">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-red-900">프로젝트 삭제: {{ $project->name }}</h3>
            <p class="mt-1 text-sm text-red-700">
                이 프로젝트를 삭제합니다. 프로젝트에 속한 모든 페이지도 함께 삭제됩니다.
            </p>
            
            <!-- 삭제될 내용 목록 -->
            <!-- 삭제 조건 안내 -->
            <div class="mt-6 bg-red-50 border border-red-200 rounded-md p-4">
                <h4 class="text-sm font-medium text-red-900 mb-2">삭제 조건:</h4>
                <ul class="text-sm text-red-800 space-y-1">
                    <li class="flex items-center">
                        @if($this->getRemainingPagesCount() === 0)
                            <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-700">모든 페이지 삭제 완료</span>
                        @else
                            <svg class="h-4 w-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-red-700">모든 페이지를 먼저 삭제해야 합니다 ({{ $this->getRemainingPagesCount() }}개 남음)</span>
                        @endif
                    </li>
                    <li class="flex items-center">
                        @if($this->confirmText === '삭제')
                            <svg class="h-4 w-4 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-green-700">삭제 확인 텍스트 입력 완료</span>
                        @else
                            <svg class="h-4 w-4 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span class="text-red-700">아래에 "삭제"를 입력하세요</span>
                        @endif
                    </li>
                </ul>
            </div>

            <!-- 삭제될 내용 목록 -->
            <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
                <h4 class="text-sm font-medium text-gray-900 mb-2">다음 내용이 삭제됩니다:</h4>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>• 프로젝트 정보 및 설정</li>
                    <li>• 프로젝트 권한 및 멤버 정보</li>
                    <li>• 연결된 샌드박스 정보</li>
                    <li>• 모든 프로젝트 관련 데이터</li>
                </ul>
            </div>

            <form wire:submit.prevent="deleteProject">
                <div class="mt-6">
                    <label for="confirm-delete" class="block text-sm font-medium text-red-700">
                        삭제를 확인하려면 <strong>"삭제"</strong>를 입력하세요
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               wire:model.live="confirmText"
                               id="confirm-delete" 
                               class="shadow-sm focus:ring-red-500 focus:border-red-500 block w-full sm:text-sm border-red-300 rounded-md" 
                               placeholder="삭제">
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('project.dashboard.project.settings.name', ['id' => $organizationId, 'projectId' => $projectId]) }}" 
                       class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </a>
                    <button type="submit" 
                            @disabled(!$this->canDelete)
                            class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2
                                   {{ $this->canDelete ? 'bg-red-600 hover:bg-red-700 focus:ring-red-500' : 'bg-red-300 cursor-not-allowed' }}">
                        @if($this->getRemainingPagesCount() > 0)
                            프로젝트 삭제 (페이지 {{ $this->getRemainingPagesCount() }}개 삭제 필요)
                        @else
                            프로젝트 삭제
                        @endif
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- 추가 정보 -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <h4 class="text-sm font-medium text-blue-900 mb-2">소프트 삭제 정보:</h4>
        <ul class="text-sm text-blue-800 space-y-1">
            <li>• 삭제된 프로젝트는 관리자가 복구할 수 있습니다</li>
            <li>• 일반 사용자에게는 더 이상 표시되지 않습니다</li>
            <li>• 30일 후 완전히 삭제될 수 있습니다 (정책에 따라)</li>
        </ul>
    </div>
</div>