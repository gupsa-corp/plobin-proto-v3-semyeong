<div class="px-6 py-6">
    <!-- 프로젝트 설정 탭 네비게이션 -->
    @include('300-page-service.314-page-project-settings-name.100-tab-navigation')

    <!-- 프로젝트로 이동 버튼 -->
    <div class="mb-6">
        <a href="{{ route('project.dashboard', ['id' => $organizationId, 'projectId' => $projectId]) }}"
           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            프로젝트로 이동
        </a>
    </div>

    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- 프로젝트 이름 변경 폼 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 이름 변경</h3>
            <p class="mt-1 text-sm text-gray-500">
                프로젝트의 이름을 변경할 수 있습니다. 이 변경은 모든 팀원에게 표시됩니다.
            </p>

            <form wire:submit.prevent="updateName">
                <div class="mt-6">
                    <label for="project-name" class="block text-sm font-medium text-gray-700">새 프로젝트 이름</label>
                    <div class="mt-1">
                        <input type="text"
                               wire:model.defer="projectName"
                               id="project-name"
                               class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md @error('projectName') border-red-300 @enderror"
                               placeholder="프로젝트 이름을 입력하세요">
                    </div>
                    @error('projectName')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button"
                            wire:click="cancel"
                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        취소
                    </button>
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50 cursor-not-allowed"
                            class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <span wire:loading.remove>변경 저장</span>
                        <span wire:loading>저장 중...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
