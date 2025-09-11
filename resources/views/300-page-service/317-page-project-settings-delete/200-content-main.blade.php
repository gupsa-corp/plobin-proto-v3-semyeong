<!-- 프로젝트 삭제 콘텐츠 -->
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

    <!-- Livewire 프로젝트 삭제 컴포넌트 -->
    @livewire('project-delete', ['projectId' => request()->route('projectId'), 'organizationId' => request()->route('id')])

    <!-- 추가 정보 -->
    <div class="mt-6 bg-gray-50 border border-gray-200 rounded-md p-4">
        <h4 class="text-sm font-medium text-gray-900 mb-2">삭제 전 확인사항:</h4>
        <ul class="text-sm text-gray-700 space-y-1">
            <li>• 중요한 데이터가 백업되었는지 확인하세요</li>
            <li>• 다른 팀원들에게 삭제 계획을 알렸는지 확인하세요</li>
            <li>• 프로덕션 환경에 배포된 내용이 있다면 먼저 처리하세요</li>
        </ul>
    </div>
</div>