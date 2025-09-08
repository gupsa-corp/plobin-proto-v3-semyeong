<!-- 내 프로젝트 목록 블록 -->
<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg leading-6 font-medium text-gray-900">내 프로젝트</h3>
            <a href="/organizations" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                전체 보기
            </a>
        </div>
    </div>

    <div class="px-6 py-4">
        @if(isset($projects) && $projects->count() > 0)
            <div class="space-y-4">
                @foreach($projects as $project)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $project->name }}
                                        </p>
                                        @if($project->user_id == auth()->id())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                소유자
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $project->organization_name }}
                                    </p>
                                    @if($project->description)
                                        <p class="text-xs text-gray-400 mt-1 truncate">
                                            {{ $project->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <div class="text-right">
                                <p class="text-xs text-gray-400">
                                    {{ $project->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <a href="{{ route('project.dashboard', ['id' => $project->organization_id, 'projectId' => $project->id]) }}"
                               class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                열기
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">프로젝트가 없습니다</h3>
                <p class="mt-1 text-sm text-gray-500">새로운 프로젝트를 만들거나 기존 프로젝트에 참여해보세요.</p>
                <div class="mt-6">
                    <a href="/organizations" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        조직 관리
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
