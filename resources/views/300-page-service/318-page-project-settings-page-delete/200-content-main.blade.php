<!-- 페이지 삭제 콘텐츠 -->
<div class="px-6 py-6">
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

    <!-- Livewire 페이지 삭제 컴포넌트 -->
    @livewire('project-page-delete', ['projectId' => request()->route('projectId'), 'organizationId' => request()->route('id')])

    <!-- 추가 정보 -->
    <div class="mt-6 bg-orange-50 border border-orange-200 rounded-md p-4">
        <h4 class="text-sm font-medium text-orange-900 mb-2">페이지 삭제 주의사항:</h4>
        <ul class="text-sm text-orange-700 space-y-1">
            <li>• 삭제된 페이지는 복구할 수 없습니다</li>
            <li>• 페이지에 포함된 모든 데이터가 완전히 제거됩니다</li>
            <li>• 다른 페이지에서 참조하는 링크가 있는지 확인하세요</li>
            <li>• 중요한 페이지의 경우 백업을 먼저 생성하세요</li>
        </ul>
    </div>
</div>