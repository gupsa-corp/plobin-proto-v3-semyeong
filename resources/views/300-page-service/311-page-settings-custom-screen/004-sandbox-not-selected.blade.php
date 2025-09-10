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