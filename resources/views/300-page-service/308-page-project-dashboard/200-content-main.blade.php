@php
    // 라우트에서 전달받은 변수들 사용
    // $organization, $project, $page 변수들이 라우트에서 전달됨

    // 안전한 변수 할당 (변수가 정의되지 않은 경우 null로 처리)
    $organization = $organization ?? null;
    $project = $project ?? null;
    $page = $page ?? null;
    $customScreen = $customScreen ?? null;

    $organizationId = $organization ? $organization->id : null;
    $projectId = $project ? $project->id : null;
    $pageId = $page ? $page->id : null;

    // 프로젝트 레벨 또는 페이지 레벨에서 샌드박스 설정 확인
    $hasSandbox = false;
    $hasCustomScreen = false;
    $sandboxType = null;
    $sandboxLevel = null; // 'page' 또는 'project' 구분용

    // 우선순위: 페이지 레벨 > 프로젝트 레벨
    if ($page && !empty($page->sandbox_type)) {
        $sandboxType = $page->sandbox_type;
        $sandboxLevel = 'page';
        $hasSandbox = true;
        $hasCustomScreen = !empty($page->custom_screen_settings);
    } elseif ($project && !empty($project->sandbox_type)) {
        $sandboxType = $project->sandbox_type;
        $sandboxLevel = 'project';
        $hasSandbox = true;
        $hasCustomScreen = false; // 프로젝트 레벨에서는 커스텀 화면 설정 없음
    }
@endphp

<!-- 페이지별 커스텀 콘텐츠 -->
<div class="px-6 py-6" x-data="">
    @if(!$organization)
        <!-- 조직이 없는 경우 -->
        <div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-red-200 shadow-sm">
            <div class="text-center max-w-md">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-red-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-red-800 mb-2">
                    조직을 찾을 수 없습니다
                </h2>

                <p class="text-red-600 mb-6">
                    요청한 조직이 존재하지 않거나 접근 권한이 없습니다.
                </p>

                <div class="space-y-3">
                    <a href="/organizations"
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                        </svg>
                        조직 목록으로 이동
                    </a>
                </div>
            </div>
        </div>
    @elseif(!$project)
        <!-- 프로젝트가 없는 경우 -->
        <div class="flex flex-col items-center justify-center min-h-96 bg-white rounded-lg border border-yellow-200 shadow-sm">
            <div class="text-center max-w-md">
                <div class="mb-6">
                    <svg class="w-16 h-16 text-yellow-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>

                <h2 class="text-xl font-semibold text-yellow-800 mb-2">
                    프로젝트를 찾을 수 없습니다
                </h2>

                <p class="text-yellow-600 mb-6">
                    요청한 프로젝트가 존재하지 않거나 접근 권한이 없습니다.
                </p>

                <div class="space-y-3">
                    <a href="/organizations/{{ $organizationId }}/projects"
                       class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-6m-2-5.5v-.5m0 0V15a2 2 0 011.5-1.943L15 13V9a2 2 0 012-2h1a2 2 0 012 2v4l-1.943 1.5A2 2 0 0119 15v.5m0 0v.5M13 21h6"/>
                        </svg>
                        프로젝트 목록으로 이동
                    </a>
                </div>
            </div>
        </div>
    @elseif(isset($customScreen) && !empty($customScreen))
        <!-- 커스텀 화면이 있는 경우 렌더링 -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- 샌드박스 정보 및 설정 바 -->
            <div class="mb-4 bg-blue-50 border border-blue-200 rounded-lg p-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            </svg>
                            <span class="text-sm font-medium text-blue-800">
                                {{ ucfirst($sandboxType) }} Sandbox
                            </span>
                        </div>
                        <div class="text-sm text-blue-600">
                            커스텀 화면: {{ $customScreen['title'] ?? '이름 없음' }}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
                           class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            설정
                        </a>
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/settings/sandbox"
                           class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-md transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            프로젝트 샌드박스
                        </a>
                    </div>
                </div>
            </div>

            <!-- 커스텀 화면 헤더 -->
            <div class="mb-6 border-b border-gray-200 pb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $customScreen['title'] ?? '커스텀 화면' }}</h1>
                        <p class="text-gray-600 mt-1">{{ $customScreen['description'] ?? '' }}</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($customScreen['type'] ?? 'custom') }}
                        </span>
                        <span class="text-xs text-gray-500">{{ $customScreen['created_at'] ?? '' }}</span>
                    </div>
                </div>
            </div>

            <!-- 커스텀 화면 컨텐츠 렌더링 -->
            <div class="custom-screen-content">
                @if(!empty($customScreen['content']))
                    {!! $customScreen['content'] !!}
                @else
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-yellow-800">커스텀 화면 콘텐츠를 렌더링할 수 없습니다.</p>
                    </div>
                @endif
            </div>
        </div>
    @elseif($page && $hasSandbox)
        @if($hasCustomScreen)
            <!-- 기존 페이지 설정 기반 커스텀 화면 로직 (호환성 유지) -->
            @php
                // 커스텀 화면 설정에서 screen_id 가져오기
                $customScreenSettings = $page->custom_screen_settings;
                $screenId = isset($customScreenSettings['screen_id']) ? $customScreenSettings['screen_id'] : null;
            @endphp

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                <p class="text-yellow-800">페이지 설정을 통한 커스텀 화면 (ID: {{ $screenId }})이 설정되어 있지만, 직접 렌더링 모드를 사용중입니다.</p>
            </div>

            <!-- 설정 버튼들을 하단에 표시 -->
            <div class="mt-6 flex justify-center space-x-3">
                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    커스텀 화면 설정
                </a>

                <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/sandbox"
                   class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    샌드박스 설정 수정
                </a>
            </div>
        @else
            <!-- 샌드박스만 설정되고 커스텀 화면이 없는 경우 -->
            <div class="bg-white rounded-lg border border-green-200 shadow-sm">
                <!-- 샌드박스 정보 및 설정 바 -->
                <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                                </svg>
                                <span class="text-sm font-medium text-green-800">
                                    {{ ucfirst($sandboxType) }} Sandbox
                                    @if($sandboxLevel === 'project')
                                        <span class="text-xs font-normal text-green-700">(프로젝트 레벨)</span>
                                    @endif
                                </span>
                            </div>
                            <div class="text-sm text-green-600">
                                @if($sandboxLevel === 'project')
                                    프로젝트 전체에 적용됨
                                @else
                                    커스텀 화면 미설정
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen"
                               class="inline-flex items-center px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                설정
                            </a>
                            <a href="/sandbox/custom-screens"
                               class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-md transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                샌드박스
                            </a>
                        </div>
                    </div>
                </div>

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
                            현재 <strong>{{ ucfirst($sandboxType) }}</strong> 샌드박스가
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
            </div>
        @endif
    @else
        <!-- 빈 페이지 안내 -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <!-- 페이지 정보 및 설정 바 -->
            <div class="mb-4 bg-gray-50 border border-gray-200 rounded-lg p-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-800">
                                페이지 설정 없음
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            샌드박스 또는 커스텀 화면 설정 필요
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($page)
                            <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/sandbox"
                               class="inline-flex items-center px-3 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                설정
                            </a>
                        @endif
                        <a href="/organizations/{{ $organizationId }}/projects/{{ $projectId }}/settings/sandbox"
                           class="inline-flex items-center px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-medium rounded-md transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            프로젝트 샌드박스
                        </a>
                    </div>
                </div>
            </div>

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
        </div>
    @endif
</div>

<!-- Livewire 에러 처리 JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Livewire 에러 리스너
    window.addEventListener('livewire:error', function(e) {
        console.error('Livewire Error:', e.detail);

        // 사용자에게 친화적인 에러 메시지 표시
        const errorContainer = document.createElement('div');
        errorContainer.className = 'fixed top-4 right-4 bg-red-500 text-white px-4 py-3 rounded-lg shadow-lg z-50';
        errorContainer.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 18.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span>페이지 로딩 중 문제가 발생했습니다. 페이지를 새로고침해주세요.</span>
                <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(errorContainer);

        // 5초 후 자동 제거
        setTimeout(function() {
            if (errorContainer.parentNode) {
                errorContainer.parentNode.removeChild(errorContainer);
            }
        }, 5000);
    });

    // 일반 JavaScript 에러 처리
    window.addEventListener('error', function(e) {
        if (e.message.includes('Livewire') || e.message.includes('Component not found')) {
            console.error('Livewire Component Error:', e.message);

            // 컴포넌트를 찾을 수 없는 경우 페이지 새로고침 제안
            if (e.message.includes('Component not found')) {
                const refreshContainer = document.createElement('div');
                refreshContainer.className = 'fixed bottom-4 right-4 bg-yellow-500 text-white px-4 py-3 rounded-lg shadow-lg z-50';
                refreshContainer.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span>컴포넌트를 찾을 수 없습니다.</span>
                        <button onclick="location.reload()" class="ml-2 bg-white text-yellow-500 px-2 py-1 rounded text-sm hover:bg-gray-100">
                            새로고침
                        </button>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;

                document.body.appendChild(refreshContainer);

                // 10초 후 자동 제거
                setTimeout(function() {
                    if (refreshContainer.parentNode) {
                        refreshContainer.parentNode.removeChild(refreshContainer);
                    }
                }, 10000);
            }
        }
    });

    // 페이지 로드 시 Livewire 상태 확인
    setTimeout(function() {
        if (typeof window.Livewire !== 'undefined') {
            console.log('Livewire loaded successfully');
        } else {
            console.warn('Livewire not loaded - some features may not work');
        }
    }, 1000);
});
</script>
