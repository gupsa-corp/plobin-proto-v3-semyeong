{{-- 프로젝트 대시보드 통일 헤더 --}}
@php
    $orgId = request()->route('id');
    $projectId = request()->route('projectId');
    $pageId = request()->route('pageId');
    $organization = \App\Models\Organization::find($orgId);
    $project = \App\Models\Project::find($projectId);
    $page = \App\Models\ProjectPage::find($pageId);

    // 페이지 상태 및 설정 확인
    // $hasSandbox는 메인 라우트에서 전달받음
    $hasCustomScreen = false;
    // $sandboxName은 메인 라우트에서 전달받음
    $sandboxLevel = null;
    $customScreen = $customScreen ?? null;
    $customScreenFolder = $customScreenFolder ?? ($page->custom_screen_folder ?? null);

    // 컨트롤러에서 전달받은 샌드박스 정보 사용
    $sandboxInfo = $sandboxInfo ?? null;
    $sandboxLevel = $sandboxInfo['sandbox_level'] ?? null;
@endphp

<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀과 브레드크럼 --}}
            <div>
                {{-- 페이지 타이틀 --}}
                @if($project)
                    <h1 class="text-xl font-semibold text-gray-900">{{ $project->name }} 대시보드</h1>
                @else
                    <h1 class="text-xl font-semibold text-gray-900">프로젝트 대시보드</h1>
                @endif

                {{-- 브레드크럼 --}}
                <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-2">
                    <a href="/organizations" class="hover:text-gray-700 transition-colors">조직</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @if($organization)
                        <a href="/organizations/{{ $orgId }}/dashboard" class="hover:text-gray-700 transition-colors">{{ $organization->name }}</a>
                    @else
                        <span class="text-gray-400">조직</span>
                    @endif
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    <a href="/organizations/{{ $orgId }}/projects" class="hover:text-gray-700 transition-colors">프로젝트</a>
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    @if($project)
                        <span class="text-gray-900 font-medium">{{ $project->name }}</span>
                    @else
                        <span class="text-gray-900 font-medium">프로젝트 대시보드</span>
                    @endif
                </nav>
            </div>

            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">

                {{-- Custom Screen Folder 선택 드롭다운 --}}
                @if($page && $hasSandbox)
                    <div class="bg-gray-50 rounded-lg shadow-sm p-3">
                        <form action="/organizations/{{ $orgId }}/projects/{{ $projectId }}/pages/{{ $pageId }}/settings/custom-screen" method="POST" class="flex items-center gap-3">
                            @csrf
                            <label for="custom_screen" class="text-sm font-medium text-gray-700 whitespace-nowrap">
                                Custom Screen:
                            </label>
                            <select name="custom_screen" id="custom_screen"
                                    class="text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    onchange="this.form.submit()">
                                <option value="">-- Select Custom Screen --</option>
                                @php
                                    // 컨트롤러에서 전달받은 커스텀 스크린 목록 사용
                                    $availableScreens = $customScreens ?? [];
                                @endphp
                                @foreach($availableScreens as $screen)
                                    <option value="{{ $screen['folder_name'] }}" {{ $customScreenFolder === $screen['folder_name'] ? 'selected' : '' }}>
                                        {{ $screen['title'] }}
                                    </option>
                                @endforeach
                            </select>
                            @if($customScreenFolder)
                                <span class="text-xs text-green-600 whitespace-nowrap">
                                    Current: {{ $customScreenFolder }}
                                </span>
                            @endif
                        </form>
                    </div>
                @endif

                {{-- 페이지 설정 드롭다운 (기존 로직 유지) --}}
                @if($page)
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                                class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md transition-colors duration-200">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                            </svg>
                            페이지 설정
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('project.dashboard.page.settings', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    일반 설정
                                </a>
                                <a href="{{ route('project.dashboard.page.settings.name', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    페이지 이름
                                </a>
                                <a href="{{ route('project.dashboard.page.settings.permissions', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                    권한 관리
                                </a>
                                <hr class="my-1 border-gray-200">
                                <a href="{{ route('project.dashboard.page.settings.custom-screen', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    커스텀 화면 설정
                                </a>
                                <a href="{{ route('project.dashboard.page.settings.deployment', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                    </svg>
                                    배포 설정
                                </a>
                                <hr class="my-1 border-gray-200">
                                <a href="{{ route('project.dashboard.page.settings.delete', ['id' => $orgId, 'projectId' => $projectId, 'pageId' => $pageId]) }}"
                                   class="flex items-center px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                    <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    페이지 삭제
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>

</div>
