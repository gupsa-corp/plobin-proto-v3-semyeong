<div class="space-y-6">
    <!-- 요약 카드들 -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">전체 프로젝트</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalProjects }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">전체 페이지</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $totalPages }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">최근 활동</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($pages) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">활성 프로젝트</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($projects) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- 프로젝트 리스트 -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">최근 프로젝트</h3>
                <p class="text-sm text-gray-500">최근 생성된 프로젝트 목록</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($projects as $project)
                    <div class="px-6 py-4 hover:bg-gray-50 cursor-pointer" wire:click="selectProject({{ $project['id'] }})">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $project['name'] }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $project['description'] ?? '설명 없음' }}</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    @if(isset($project['organization']))
                                        <span class="text-xs text-blue-600">{{ $project['organization']['name'] ?? '조직 없음' }}</span>
                                    @endif
                                    @if(isset($project['user']))
                                        <span class="text-xs text-gray-500">by {{ $project['user']['name'] ?? '사용자 없음' }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($project['created_at'])->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">프로젝트가 없습니다.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- 페이지 리스트 -->
        <div class="bg-white rounded-lg border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">
                        @if($selectedProject)
                            선택된 프로젝트의 페이지
                        @else
                            최근 페이지
                        @endif
                    </h3>
                    <p class="text-sm text-gray-500">
                        @if($selectedProject)
                            프로젝트 ID: {{ $selectedProject }}
                        @else
                            최근 생성된 페이지 목록
                        @endif
                    </p>
                </div>
                @if($selectedProject)
                    <button wire:click="showAllProjects" class="text-sm text-blue-600 hover:text-blue-800">
                        전체 보기
                    </button>
                @endif
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($pages as $page)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">{{ $page['title'] }}</h4>
                                <p class="text-xs text-gray-500 mt-1">{{ $page['slug'] ?? 'slug 없음' }}</p>
                                <div class="flex items-center mt-2 space-x-4">
                                    @if(isset($page['project']))
                                        <span class="text-xs text-green-600">{{ $page['project']['name'] ?? '프로젝트 없음' }}</span>
                                    @endif
                                    @if(isset($page['user']))
                                        <span class="text-xs text-gray-500">by {{ $page['user']['name'] ?? '사용자 없음' }}</span>
                                    @endif
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                        @if(($page['status'] ?? 'draft') === 'published') bg-green-100 text-green-800
                                        @elseif(($page['status'] ?? 'draft') === 'draft') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($page['status'] ?? 'draft') }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ \Carbon\Carbon::parse($page['created_at'])->format('Y-m-d') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <p class="text-gray-500">
                            @if($selectedProject)
                                선택된 프로젝트에 페이지가 없습니다.
                            @else
                                페이지가 없습니다.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 새로고침 버튼 -->
    <div class="flex justify-end">
        <button wire:click="loadData" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg wire:loading wire:target="loadData" class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            새로고침
        </button>
    </div>
</div>
