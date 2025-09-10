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

        @if($sandboxLevel === 'project')
            <p class="text-gray-500 mb-6">
                이 설정은 프로젝트의 모든 페이지에 적용됩니다. 개별 페이지에서 다른 샌드박스를 설정할 수도 있습니다.
            </p>
        @else
            <p class="text-gray-500 mb-6">
                커스텀 화면을 추가로 설정할 수 있습니다.
            </p>
        @endif

        <div class="space-y-3">
            <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
               class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                커스텀 화면 설정
            </a>

            <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/sandbox"
               class="inline-flex items-center justify-center px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                샌드박스 설정 수정
            </a>
        </div>
    </div>
</div>
