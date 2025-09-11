<div class="space-y-6">
    <!-- 헤더 통계 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-sm">📋</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">전체</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $stats['total_projects'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <span class="text-yellow-600 text-sm">🔄</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">진행 중</p>
                    <p class="text-lg font-semibold text-yellow-600">{{ $stats['in_progress'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <span class="text-green-600 text-sm">✅</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">완료</p>
                    <p class="text-lg font-semibold text-green-600">{{ $stats['completed'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <span class="text-red-600 text-sm">🚫</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">블록</p>
                    <p class="text-lg font-semibold text-red-600">{{ $stats['blocked'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 컨트롤 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-medium text-gray-900">프로젝트 칸반 보드</h3>
            <button wire:click="refreshData" 
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                🔄 새로고침
            </button>
        </div>
    </div>

    <!-- 칸반 보드 -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 min-h-[500px]">
            @foreach($columns as $column)
                <div class="bg-gray-50 rounded-lg p-3">
                    <!-- 칼럼 헤더 -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full 
                                @if($column['color'] === 'gray') bg-gray-400
                                @elseif($column['color'] === 'blue') bg-blue-400
                                @elseif($column['color'] === 'yellow') bg-yellow-400
                                @elseif($column['color'] === 'purple') bg-purple-400
                                @elseif($column['color'] === 'green') bg-green-400
                                @endif"></div>
                            <h4 class="text-sm font-medium text-gray-900">{{ $column['name'] }}</h4>
                        </div>
                        <span class="bg-gray-200 text-gray-600 text-xs px-2 py-1 rounded-full">
                            {{ count($projects[$column['id']] ?? []) }}
                        </span>
                    </div>

                    <!-- 프로젝트 카드들 -->
                    <div class="space-y-3">
                        @if(isset($projects[$column['id']]))
                            @foreach($projects[$column['id']] as $project)
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-200 cursor-move hover:shadow-md transition-shadow">
                                    <!-- 프로젝트 제목 -->
                                    <h5 class="text-sm font-medium text-gray-900 mb-1">{{ $project->name }}</h5>
                                    
                                    <!-- 프로젝트 설명 -->
                                    @if($project->description)
                                        <p class="text-xs text-gray-600 mb-2 line-clamp-2">{{ $project->description }}</p>
                                    @endif
                                    
                                    <!-- 메타 정보 -->
                                    <div class="flex items-center justify-between text-xs text-gray-500">
                                        <div class="flex items-center space-x-1">
                                            <span>👤</span>
                                            <span>{{ $project->created_by_name ?? '-' }}</span>
                                        </div>
                                        <div class="flex items-center space-x-1">
                                            <span>🏢</span>
                                            <span class="truncate max-w-20">{{ $project->organization_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    
                                    <!-- 생성일 -->
                                    <div class="mt-2 text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($project->created_at)->diffForHumans() }}
                                    </div>
                                    
                                    <!-- 액션 버튼 -->
                                    <div class="mt-2 flex justify-end space-x-1">
                                        <button class="text-xs text-blue-600 hover:text-blue-800">보기</button>
                                        <button class="text-xs text-green-600 hover:text-green-800">편집</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- 빈 상태 -->
                        @if(empty($projects[$column['id']]))
                            <div class="text-center py-8">
                                <div class="text-gray-300 text-2xl mb-2">📋</div>
                                <p class="text-xs text-gray-400">프로젝트가 없습니다</p>
                            </div>
                        @endif

                        <!-- 새 카드 추가 버튼 -->
                        <button class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-400 hover:border-gray-400 hover:text-gray-600 text-sm">
                            + 새 프로젝트 추가
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- 드래그 앤 드롭 안내 -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">드래그 앤 드롭</h3>
                <div class="mt-2 text-sm text-blue-700">
                    <p>프로젝트 카드를 드래그하여 다른 칼럼으로 이동할 수 있습니다. (개발 예정)</p>
                </div>
            </div>
        </div>
    </div>
</div>