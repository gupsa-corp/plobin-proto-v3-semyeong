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