<!-- 프로젝트 이름 변경 콘텐츠 -->
<div class="px-6 py-6" x-data="{ projectName: 'Sample Project Name' }">

    <!-- 프로젝트 설정 탭 네비게이션 -->
    @include('300-page-service.314-page-project-settings-name.100-tab-navigation')

    <!-- 프로젝트로 이동 버튼 -->
    <div class="mb-6">
        <a href="{{ route('project.dashboard', ['id' => request()->route('id'), 'projectId' => request()->route('projectId')]) }}"
           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            프로젝트로 이동
        </a>
    </div>

    <!-- 프로젝트 이름 변경 폼 -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">프로젝트 이름 변경</h3>
            <p class="mt-1 text-sm text-gray-500">
                프로젝트의 이름을 변경할 수 있습니다. 이 변경은 모든 팀원에게 표시됩니다.
            </p>

            <div class="mt-6">
                <label for="project-name" class="block text-sm font-medium text-gray-700">새 프로젝트 이름</label>
                <div class="mt-1">
                    <input type="text"
                           name="project-name"
                           id="project-name"
                           x-model="projectName"
                           class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                           placeholder="프로젝트 이름을 입력하세요">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button"
                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    취소
                </button>
                <button type="button"
                        class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    변경 저장
                </button>
            </div>
        </div>
    </div>
</div>
