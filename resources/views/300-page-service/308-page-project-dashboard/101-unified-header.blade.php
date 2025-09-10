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

    if ($page && !empty($page->sandbox_name)) {
        $sandboxLevel = 'page';
        $hasCustomScreen = !empty($page->custom_screen_settings);
    } elseif ($project && !empty($project->sandbox_name)) {
        $sandboxLevel = 'project';
    }
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
                {{-- 페이지 변경 드롭다운 --}}
                @if($project)
                    <div class="relative" x-data="{
                        pageDropdownOpen: false,
                        pages: [],
                        loading: false,
                        error: null,

                        async loadPages() {
                            if (this.pages.length > 0) return;

                            this.loading = true;
                            this.error = null;

                            try {
                                const response = await fetch(`/api/projects/{{ $projectId }}/pages`);
                                if (!response.ok) throw new Error('Failed to fetch pages');

                                const data = await response.json();
                                this.pages = data.pages || [];
                            } catch (error) {
                                console.error('페이지 로드 실패:', error);
                                this.error = '페이지 목록을 불러올 수 없습니다.';
                                this.pages = [];
                            } finally {
                                this.loading = false;
                            }
                        },

                        async openPageDropdown() {
                            this.pageDropdownOpen = true;
                            await this.loadPages();
                        }
                    }">
                        <button @click="openPageDropdown()"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            페이지 변경
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="pageDropdownOpen"
                             @click.away="pageDropdownOpen = false"
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-xs font-medium text-gray-500 uppercase tracking-wider border-b">
                                    프로젝트 페이지 목록
                                </div>

                                <!-- 로딩 상태 -->
                                <div x-show="loading" class="flex items-center px-4 py-3 text-sm text-gray-500">
                                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    페이지 목록을 불러오고 있습니다...
                                </div>

                                <!-- 에러 상태 -->
                                <div x-show="error" class="flex items-center px-4 py-3 text-sm text-red-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span x-text="error"></span>
                                </div>

                                <!-- 페이지가 없을 때 -->
                                <div x-show="!loading && !error && pages.length === 0" class="px-4 py-3 text-sm text-gray-500 text-center">
                                    생성된 페이지가 없습니다.
                                </div>

                                <!-- 동적으로 로드된 페이지들 -->
                                <template x-for="pageItem in pages" :key="pageItem.id">
                                    <a :href="`/organizations/{{ $orgId }}/projects/{{ $projectId }}/pages/${pageItem.id}/dashboard`"
                                       class="w-full text-left flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200"
                                       :class="{ 'bg-blue-50 text-blue-700': pageItem.id == '{{ $pageId }}' }">

                                        <svg class="w-4 h-4 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>

                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium truncate" x-text="pageItem.name"></div>
                                            <div class="text-xs text-gray-500 flex items-center space-x-2 mt-1">
                                                <span x-text="pageItem.description || '페이지 설명 없음'"></span>
                                                <span>•</span>
                                                <span x-text="new Date(pageItem.updated_at).toLocaleDateString()"></span>
                                            </div>
                                        </div>

                                        <!-- 현재 페이지 표시 -->
                                        <div x-show="pageItem.id == '{{ $pageId }}'" class="ml-2 text-blue-600">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </template>
                            </div>
                        </div>
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
