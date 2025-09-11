<!-- 샌드박스 콘텐츠 컴포넌트 -->
<div class="flex flex-col items-center justify-center min-h-96 p-6">
    <div class="text-center max-w-md">
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <h2 class="text-xl font-semibold text-green-900 mb-2">
            샌드박스가 활성화되었습니다
        </h2>

        <p class="text-green-600 mb-2">
            현재 <strong>{{ ucfirst($sandboxName) }}</strong> 샌드박스가
            @if($sandboxLevel === 'project')
                <span class="font-semibold">프로젝트 레벨</span>에서 설정되어 있습니다.
            @else
                설정되어 있습니다.
            @endif
        </p>

        <p class="text-gray-500 mb-6">
            이 설정은 프로젝트의 모든 페이지에 적용됩니다. 커스텀 화면을 추가로 설정할 수 있습니다.
        </p>

        <div class="space-y-3">
            <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                커스텀 화면 설정
            </a>
        </div>
    </div>
</div>
