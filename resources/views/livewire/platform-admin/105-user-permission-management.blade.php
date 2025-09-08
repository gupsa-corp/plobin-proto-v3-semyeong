{{-- 사용자 권한 관리 Livewire 컴포넌트 --}}
<div class="space-y-6">
    {{-- 검색 및 필터 --}}
    <div class="bg-white shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search"
                       placeholder="이름 또는 이메일로 검색..."
                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            
            <div>
                <select wire:model.live="selectedRole" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">모든 역할</option>
                    <option value="no_role">역할 없음</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <select wire:model.live="selectedOrganization"
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">모든 조직</option>
                    <option value="no_org">조직 소속 없음</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}">{{ $org->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <button wire:click="clearFilters" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    필터 초기화
                </button>
            </div>
        </div>
    </div>

    {{-- 사용자 테이블 --}}
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">사용자 목록</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                전체 {{ $users->total() }}명의 사용자
            </p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">사용자</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">역할</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">조직 권한</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">작업</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($users as $user)
                        <tr>
                            {{-- 사용자 정보 --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            
                            {{-- 역할 --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            역할 없음
                                        </span>
                                    @endforelse
                                </div>
                            </td>
                            
                            {{-- 조직 권한 --}}
                            <td class="px-6 py-4">
                                <div class="space-y-1">
                                    @forelse($user->organizations as $org)
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-600">{{ $org->name }}</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $org->pivot->role ?? 'member' }}
                                            </span>
                                        </div>
                                    @empty
                                        <span class="text-xs text-gray-500">조직 소속 없음</span>
                                    @endforelse
                                </div>
                            </td>
                            
                            {{-- 상태 --}}
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->is_active ?? true)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        활성
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        비활성
                                    </span>
                                @endif
                            </td>
                            
                            {{-- 작업 --}}
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button wire:click="openRoleChangeModal({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        역할변경
                                    </button>
                                    <button wire:click="toggleUserStatus({{ $user->id }})"
                                            class="text-yellow-600 hover:text-yellow-900">
                                        상태변경
                                    </button>
                                    <button wire:click="openTenantPermissionModal({{ $user->id }})"
                                            class="text-green-600 hover:text-green-900">
                                        조직권한
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                검색 결과가 없습니다.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- 페이지네이션 --}}
        <div class="px-6 py-3 bg-gray-50">
            {{ $users->links() }}
        </div>
    </div>

    {{-- 역할 변경 모달 --}}
    @if($showRoleChangeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">역할 변경</h3>
                        
                        @if($selectedUser)
                            <p class="text-sm text-gray-600 mb-4">
                                <strong>{{ $selectedUser->name }}</strong>의 역할을 변경합니다.
                            </p>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">새 역할 선택</label>
                                <select wire:model="newRole" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">역할을 선택하세요</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @error('newRole') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="saveRoleChange"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            변경
                        </button>
                        <button wire:click="closeRoleChangeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 상태 변경 모달 --}}
    @if($showStatusChangeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">사용자 상태 변경</h3>
                        <p class="text-sm text-gray-600">{{ $statusChangeMessage }}</p>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="confirmStatusChange"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            확인
                        </button>
                        <button wire:click="closeStatusChangeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 테넌트 권한 모달 --}}
    @if($showTenantPermissionModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">조직 권한 관리</h3>
                        
                        @if($selectedUser)
                            <p class="text-sm text-gray-600 mb-6">
                                <strong>{{ $selectedUser->name }}</strong>의 조직별 권한을 관리합니다.
                            </p>
                            
                            <div class="space-y-6">
                                {{-- 현재 권한 목록 --}}
                                <div>
                                    <h4 class="text-md font-medium text-gray-900 mb-3">현재 조직 권한</h4>
                                    <div class="space-y-2">
                                        @forelse($tenantPermissions as $index => $permission)
                                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                                <div>
                                                    <span class="font-medium">{{ $permission['organization_name'] }}</span>
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $permission['role'] }}
                                                    </span>
                                                </div>
                                                <div class="flex space-x-2">
                                                    <button class="text-indigo-600 hover:text-indigo-900 text-sm">수정</button>
                                                    <button class="text-red-600 hover:text-red-900 text-sm">제거</button>
                                                </div>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500">현재 조직 권한이 없습니다.</p>
                                        @endforelse
                                    </div>
                                </div>
                                
                                {{-- 새 권한 추가 --}}
                                <div>
                                    <h4 class="text-md font-medium text-gray-900 mb-3">새 조직 권한 추가</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">조직</label>
                                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="">조직을 선택하세요</option>
                                                @foreach($organizations as $org)
                                                    <option value="{{ $org->id }}">{{ $org->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">역할</label>
                                            <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="">역할을 선택하세요</option>
                                                <option value="member">멤버</option>
                                                <option value="admin">관리자</option>
                                                <option value="owner">소유자</option>
                                            </select>
                                        </div>
                                        <div class="flex items-end">
                                            <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                추가
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            저장
                        </button>
                        <button wire:click="closeTenantPermissionModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>