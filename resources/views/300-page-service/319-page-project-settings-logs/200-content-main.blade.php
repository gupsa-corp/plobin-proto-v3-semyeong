<!-- 변경 로그 콘텐츠 -->
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

    <!-- Livewire 변경 로그 컴포넌트 -->
    @livewire('project-settings-logs', ['projectId' => request()->route('projectId')])

    <!-- 로그 정보 -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
        <h4 class="text-sm font-medium text-blue-900 mb-2">변경 로그 정보:</h4>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• 프로젝트의 모든 설정 변경 이력이 기록됩니다</li>
            <li>• 페이지 생성, 수정, 삭제 내역을 확인할 수 있습니다</li>
            <li>• 사용자 권한 변경 내역을 추적할 수 있습니다</li>
            <li>• 로그는 삭제할 수 없으며 시스템에 영구 보관됩니다</li>
        </ul>
    </div>
</div>