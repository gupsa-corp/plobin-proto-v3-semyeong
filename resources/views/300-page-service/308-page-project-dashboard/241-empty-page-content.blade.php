<!-- 빈 페이지 콘텐츠 컴포넌트 -->
<div class="flex flex-col items-center justify-center min-h-96 p-6">
    <div class="text-center max-w-md">
        <div class="mb-6">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        <h2 class="text-xl font-semibold text-gray-900 mb-2">
            페이지 콘텐츠가 없습니다
        </h2>

        <p class="text-gray-500 mb-6">
            이 페이지는 아직 설정되지 않았습니다.<br>
            페이지 설정에서 샌드박스 설정 또는 커스텀 화면을 구성해주세요.
        </p>

        <div class="space-y-3">
            @if($page)
                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    커스텀 화면 설정하기
                </a>

                <div class="text-sm text-gray-400">
                    또는 샌드박스 설정에서 바로 시작하세요
                </div>

                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/sandbox"
                   class="inline-flex items-center justify-center px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    샌드박스 설정하기
                </a>
            @else
                <div class="text-sm text-gray-500 mb-4">
                    프로젝트에 페이지가 없습니다. 먼저 페이지를 생성해주세요.
                </div>

                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    페이지 생성하기
                </a>
            @endif
        </div>
    </div>
</div>
