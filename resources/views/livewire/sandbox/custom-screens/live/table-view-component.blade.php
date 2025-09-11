<div class="space-y-6">
    <!-- 헤더 통계 -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <span class="text-blue-600 text-sm">📊</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">전체 프로젝트</p>
                    <p class="text-lg font-semibold text-blue-600">{{ $stats['total_projects'] }}</p>
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
                    <p class="text-sm font-medium text-gray-900">진행 중</p>
                    <p class="text-lg font-semibold text-green-600">{{ $stats['active_projects'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                        <span class="text-purple-600 text-sm">🎯</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">완료</p>
                    <p class="text-lg font-semibold text-purple-600">{{ $stats['completed_projects'] }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <span class="text-orange-600 text-sm">🏢</span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-900">조직</p>
                    <p class="text-lg font-semibold text-orange-600">{{ $stats['total_organizations'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 필터 및 검색 -->
    <div class="bg-white p-4 rounded-lg border border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-4">
                <!-- 검색 -->
                <div class="relative">
                    <input wire:model.live="search" 
                           type="text" 
                           placeholder="프로젝트명, 설명, 생성자 검색..."
                           class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <!-- 상태 필터 -->
                <select wire:model.live="filterStatus" 
                        class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">전체 상태</option>
                    <option value="active">활성</option>
                    <option value="in_progress">진행 중</option>
                    <option value="completed">완료</option>
                    <option value="paused">일시정지</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button wire:click="refreshData" 
                        class="px-3 py-2 text-sm bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                    🔄 새로고침
                </button>
            </div>
        </div>
    </div>

    <!-- 테이블 -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('name')" class="group flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-900">
                                <span>프로젝트명</span>
                                @if($sortBy === 'name')
                                    <span class="text-blue-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('status')" class="group flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-900">
                                <span>상태</span>
                                @if($sortBy === 'status')
                                    <span class="text-blue-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">조직</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">생성자</th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('created_at')" class="group flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-900">
                                <span>생성일</span>
                                @if($sortBy === 'created_at')
                                    <span class="text-blue-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left">
                            <button wire:click="sortBy('updated_at')" class="group flex items-center space-x-1 text-xs font-medium text-gray-500 uppercase tracking-wider hover:text-gray-900">
                                <span>수정일</span>
                                @if($sortBy === 'updated_at')
                                    <span class="text-blue-600">{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span>
                                @endif
                            </button>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                    @if($project->description)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $project->description }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full
                                    @if($project->status === 'active') bg-green-100 text-green-800
                                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($project->status === 'completed') bg-purple-100 text-purple-800
                                    @elseif($project->status === 'paused') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    @if($project->status === 'active') ✅ 활성
                                    @elseif($project->status === 'in_progress') 🔄 진행중
                                    @elseif($project->status === 'completed') 🎯 완료
                                    @elseif($project->status === 'paused') ⏸️ 일시정지
                                    @else {{ $project->status }}
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $project->organization_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $project->created_by_name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($project->created_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($project->updated_at)->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="text-blue-600 hover:text-blue-900 mr-3">보기</button>
                                <button class="text-green-600 hover:text-green-900 mr-3">편집</button>
                                <button class="text-red-600 hover:text-red-900">삭제</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-400 text-4xl mb-2">📋</div>
                                <p class="text-gray-500">검색 조건에 맞는 프로젝트가 없습니다.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 페이지네이션 -->
        @if(count($projects) > 0)
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        총 <span class="font-medium">{{ $stats['total_projects'] }}</span>개 프로젝트
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>