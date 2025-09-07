<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-gray-900">역할 관리</h2>
        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            새 역할 생성
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 역할 목록 -->
        <div class="lg:col-span-2">
            <div class="space-y-4">
                @foreach($roles as $role)
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                         wire:click="selectRole({{ $role['id'] }})">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @switch($role['display_info']['color'])
                                        @case('blue') bg-blue-100 text-blue-800 @break
                                        @case('green') bg-green-100 text-green-800 @break
                                        @case('purple') bg-purple-100 text-purple-800 @break
                                        @case('red') bg-red-100 text-red-800 @break
                                        @case('gray') bg-gray-100 text-gray-800 @break
                                        @default bg-indigo-100 text-indigo-800
                                    @endswitch">
                                    {{ $role['display_info']['label'] }}
                                </span>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">{{ $role['name'] }}</h3>
                                    <p class="text-sm text-gray-500">{{ $role['display_info']['description'] }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                        <span>{{ $role['permissions_count'] }} 권한</span>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center space-x-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        <span>{{ $role['users_count'] }} 사용자</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button wire:click.stop="openEditModal({{ $role['id'] }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click.stop="openDeleteModal({{ $role['id'] }})" 
                                            class="text-red-600 hover:text-red-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- 선택된 역할 상세 -->
        <div class="lg:col-span-1">
            @if($selectedRole)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $selectedRole['display_info']['label'] }}</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">역할명</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRole['name'] }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Guard</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRole['guard_name'] }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">설명</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRole['display_info']['description'] }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">사용자 수</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRole['users_count'] }}명</dd>
                        </div>
                        
                        @if(count($selectedRole['permissions']) > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">권한 목록</dt>
                                <dd class="space-y-1">
                                    @foreach($selectedRole['permissions'] as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1 mb-1">
                                            {{ $permission }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">역할을 선택하세요</h3>
                    <p class="mt-1 text-sm text-gray-500">역할을 클릭하면 상세 정보를 볼 수 있습니다.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- 역할 생성 모달 -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="createRole">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">새 역할 생성</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">역할명</label>
                                    <input type="text" wire:model="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="guard_name" class="block text-sm font-medium text-gray-700">Guard</label>
                                    <input type="text" wire:model="guard_name" id="guard_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('guard_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">권한 선택</label>
                                    @foreach($permissions as $category => $categoryPermissions)
                                        <div class="mb-3">
                                            <h4 class="font-medium text-gray-800">{{ $category }}</h4>
                                            <div class="mt-2 space-y-2">
                                                @foreach($categoryPermissions as $permission)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                생성
                            </button>
                            <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                취소
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- 역할 수정 모달 -->
    @if($showEditModal && $editingRole)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="updateRole">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">역할 수정</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_name" class="block text-sm font-medium text-gray-700">역할명</label>
                                    <input type="text" wire:model="name" id="edit_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="edit_guard_name" class="block text-sm font-medium text-gray-700">Guard</label>
                                    <input type="text" wire:model="guard_name" id="edit_guard_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('guard_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">권한 선택</label>
                                    @foreach($permissions as $category => $categoryPermissions)
                                        <div class="mb-3">
                                            <h4 class="font-medium text-gray-800">{{ $category }}</h4>
                                            <div class="mt-2 space-y-2">
                                                @foreach($categoryPermissions as $permission)
                                                    <label class="flex items-center">
                                                        <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                수정
                            </button>
                            <button type="button" wire:click="closeModals" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                취소
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- 역할 삭제 모달 -->
    @if($showDeleteModal && $editingRole)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">역할 삭제</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        정말로 '<strong>{{ $editingRole->name }}</strong>' 역할을 삭제하시겠습니까? 
                                        이 작업은 되돌릴 수 없습니다.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="deleteRole" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                            삭제
                        </button>
                        <button wire:click="closeModals" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>