{{-- 권한 매트릭스 관리 Livewire 컴포넌트 --}}
<div class="space-y-6">
    {{-- 스코프 선택 --}}
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-lg shadow-lg overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-white mb-6">권한 관리 범위 선택</h2>
            
            <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- 플랫폼 스코프 --}}
                    <label class="relative">
                        <input type="radio" 
                               wire:model.live="scope" 
                               value="platform" 
                               class="sr-only">
                        <div class="flex items-center p-4 bg-white bg-opacity-10 rounded-lg border-2 border-transparent hover:border-white cursor-pointer transition-all
                                    @if($scope === 'platform') border-white bg-opacity-20 @endif">
                            <div class="flex-shrink-0">
                                <span class="text-2xl">🏢</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-white">플랫폼 권한</div>
                                <div class="text-sm text-purple-200">전체 플랫폼의 역할과 권한을 관리합니다</div>
                            </div>
                        </div>
                    </label>

                    {{-- 조직 스코프 --}}
                    <label class="relative">
                        <input type="radio" 
                               wire:model.live="scope" 
                               value="organization" 
                               class="sr-only">
                        <div class="flex items-center p-4 bg-white bg-opacity-10 rounded-lg border-2 border-transparent hover:border-white cursor-pointer transition-all
                                    @if($scope === 'organization') border-white bg-opacity-20 @endif">
                            <div class="flex-shrink-0">
                                <span class="text-2xl">🏢</span>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-white">조직 권한</div>
                                <div class="text-sm text-purple-200">특정 조직의 역할과 권한을 관리합니다</div>
                            </div>
                        </div>
                    </label>
                </div>

                {{-- 조직 선택 (조직 스코프 선택시에만 표시) --}}
                @if($scope === 'organization')
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-white mb-2">조직 선택</label>
                        <select wire:model.live="selectedOrganizationId"
                                class="block w-full rounded-md border-gray-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">조직을 선택하세요</option>
                            @foreach($organizations as $org)
                                <option value="{{ $org->id }}">{{ $org->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 통계 카드 --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 2.676-.732 5.016-2.297 6.894-4.622.058-.072.12-.144.18-.218A11.955 11.955 0 0021 9a12.02 12.02 0 00-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">총 권한</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_permissions'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">활성 역할</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_roles'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">권한 카테고리</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_categories'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 컨트롤 패널 --}}
    <div id="control-panel" class="bg-white shadow rounded-lg p-6" 
         style="display: {{ ($scope === 'platform' || ($scope === 'organization' && $selectedOrganizationId)) ? 'block' : 'none' }}">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
            <div class="flex space-x-4">
                {{-- 검색 --}}
                <div class="relative">
                    <input type="text" 
                           wire:model.live.debounce.300ms="searchTerm"
                           placeholder="권한 검색..."
                           class="block w-64 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>

                {{-- 카테고리 필터 --}}
                <div>
                    <select wire:model.live="selectedCategory"
                            class="block w-48 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        <option value="">모든 카테고리</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}">{{ $category }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- 작업 버튼 --}}
            <div class="flex space-x-2">
                <button wire:click="exportPermissions"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    내보내기
                </button>
                
                <button onclick="openCreatePermissionModal()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    권한 생성
                </button>
            </div>
        </div>
    </div>

    {{-- 권한 매트릭스 --}}
    <div id="permissions-matrix" class="bg-white shadow rounded-lg overflow-hidden" 
         style="display: {{ ($scope === 'platform' || ($scope === 'organization' && $selectedOrganizationId)) ? 'block' : 'none' }}">
        
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">권한 매트릭스</h3>
            <p class="mt-1 text-sm text-gray-500">
                역할과 권한의 매핑 관계를 확인하고 수정할 수 있습니다.
            </p>
        </div>

        @if(!empty($permissionMatrix) && !empty($rolesData))
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    {{-- 헤더 --}}
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="sticky left-0 z-10 bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-r border-gray-200">
                                권한
                            </th>
                            @foreach($rolesData as $role)
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <div class="flex flex-col items-center space-y-2">
                                        <span>{{ $role }}</span>
                                        <button wire:click="selectAllForRole('{{ $role }}')"
                                                class="text-xs text-blue-600 hover:text-blue-800 font-normal">
                                            전체선택
                                        </button>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>

                    {{-- 본문 --}}
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($permissionsData as $category => $permissions)
                            {{-- 카테고리 헤더 --}}
                            <tr class="category-header bg-gray-100" data-category="{{ $category }}">
                                <td colspan="{{ count($rolesData) + 1 }}" class="px-6 py-3 text-sm font-semibold text-gray-900">
                                    {{ $category }}
                                </td>
                            </tr>

                            {{-- 권한 행들 --}}
                            @foreach($permissions as $permission)
                                <tr class="permission-row hover:bg-gray-50" 
                                    data-permission="{{ $permission['name'] }}" 
                                    data-category="{{ $category }}">
                                    
                                    {{-- 권한 이름 --}}
                                    <td class="sticky left-0 z-10 bg-white px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-200">
                                        <div>
                                            <div>{{ $permission['name'] }}</div>
                                            @if($permission['description'])
                                                <div class="text-xs text-gray-500">{{ $permission['description'] }}</div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 역할별 체크박스 --}}
                                    @foreach($rolesData as $role)
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <input type="checkbox" 
                                                   class="permission-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                                   {{ isset($permissionMatrix[$role][$category][$permission['name']]) && $permissionMatrix[$role][$category][$permission['name']] ? 'checked' : '' }}
                                                   wire:click="toggleRolePermission('{{ $role }}', '{{ $permission['name'] }}', $event.target.checked)">
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 2.676-.732 5.016-2.297 6.894-4.622.058-.072.12-.144.18-.218A11.955 11.955 0 0021 9a12.02 12.02 0 00-.382-3.016z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">권한 데이터 없음</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($scope === 'organization' && !$selectedOrganizationId)
                        조직을 선택하여 권한 매트릭스를 확인하세요.
                    @else
                        권한 또는 역할 데이터가 없습니다.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

{{-- 스크립트 섹션 --}}
@script
<script>
// 권한 생성 모달 열기
function openCreatePermissionModal() {
    const name = prompt('권한 이름을 입력하세요:');
    if (!name) return;
    
    const category = prompt('카테고리를 입력하세요:');
    if (!category) return;
    
    const description = prompt('설명을 입력하세요 (선택사항):') || '';
    
    $wire.call('createPermission', name, category, description);
}

// 다운로드 이벤트 리스너
$wire.on('download-export', (event) => {
    const data = event.data;
    const filename = event.filename;
    
    const dataStr = JSON.stringify(data, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    const url = URL.createObjectURL(dataBlob);
    
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    link.click();
    
    URL.revokeObjectURL(url);
});
</script>
@endscript