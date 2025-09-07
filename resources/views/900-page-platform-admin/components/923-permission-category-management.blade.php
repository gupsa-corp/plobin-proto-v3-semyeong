<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-gray-900">권한 및 카테고리 관리</h2>
        <div class="flex space-x-3">
            <button wire:click="openCreateCategoryModal" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                카테고리 생성
            </button>
            <button wire:click="openCreatePermissionModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                권한 생성
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 카테고리 목록 -->
        <div class="lg:col-span-1">
            <h3 class="text-lg font-medium text-gray-900 mb-4">권한 카테고리</h3>
            <div class="space-y-3">
                @foreach($categories as $category)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $category['display_name'] }}</h4>
                                <p class="text-xs text-gray-500">{{ $category['name'] }}</p>
                                @if($category['description'])
                                    <p class="text-sm text-gray-600 mt-1">{{ $category['description'] }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-2">{{ $category['permissions_count'] }}개 권한</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($category['is_active'])
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">활성</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">비활성</span>
                                @endif
                                <div class="flex space-x-1">
                                    <button wire:click="openEditCategoryModal({{ $category['id'] }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button wire:click="openDeleteCategoryModal({{ $category['id'] }})" 
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
                
                @if(empty($categories))
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">카테고리 없음</h3>
                        <p class="mt-1 text-sm text-gray-500">카테고리를 생성해보세요.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 권한 목록 -->
        <div class="lg:col-span-2">
            <h3 class="text-lg font-medium text-gray-900 mb-4">권한 목록</h3>
            
            @if(count($permissions) > 0)
                <div class="space-y-6">
                    @foreach($permissions as $category => $categoryPermissions)
                        <div>
                            <h4 class="text-md font-medium text-gray-800 mb-3">{{ $category }}</h4>
                            <div class="space-y-2">
                                @foreach($categoryPermissions as $permission)
                                    <div class="bg-white border border-gray-200 rounded-lg p-3 hover:shadow-sm transition-shadow cursor-pointer"
                                         wire:click="selectPermission({{ $permission['id'] }})">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h5 class="text-sm font-medium text-gray-900">{{ $permission['name'] }}</h5>
                                                <div class="flex items-center space-x-4 mt-1">
                                                    <span class="text-xs text-gray-500">Guard: {{ $permission['guard_name'] }}</span>
                                                    <span class="text-xs text-gray-500">{{ $permission['roles_count'] }}개 역할에 할당됨</span>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button wire:click.stop="openEditPermissionModal({{ $permission['id'] }})" 
                                                        class="text-indigo-600 hover:text-indigo-900">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click.stop="openDeletePermissionModal({{ $permission['id'] }})" 
                                                        class="text-red-600 hover:text-red-900">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                        
                                        @if(count($permission['roles']) > 0)
                                            <div class="mt-2">
                                                @foreach($permission['roles'] as $role)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                        {{ $role }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">권한 없음</h3>
                    <p class="mt-1 text-sm text-gray-500">권한을 생성해보세요.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- 권한 생성 모달 -->
    @if($showCreatePermissionModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="createPermission">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">새 권한 생성</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="permissionName" class="block text-sm font-medium text-gray-700">권한명</label>
                                    <input type="text" wire:model="permissionName" id="permissionName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('permissionName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="permissionGuardName" class="block text-sm font-medium text-gray-700">Guard</label>
                                    <input type="text" wire:model="permissionGuardName" id="permissionGuardName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('permissionGuardName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
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

    <!-- 카테고리 생성 모달 -->
    @if($showCreateCategoryModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="createCategory">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">새 카테고리 생성</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="categoryName" class="block text-sm font-medium text-gray-700">카테고리명</label>
                                    <input type="text" wire:model="categoryName" id="categoryName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('categoryName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="categoryDisplayName" class="block text-sm font-medium text-gray-700">표시명</label>
                                    <input type="text" wire:model="categoryDisplayName" id="categoryDisplayName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('categoryDisplayName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="categoryDescription" class="block text-sm font-medium text-gray-700">설명</label>
                                    <textarea wire:model="categoryDescription" id="categoryDescription" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    @error('categoryDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label for="categorySortOrder" class="block text-sm font-medium text-gray-700">정렬 순서</label>
                                        <input type="number" wire:model="categorySortOrder" id="categorySortOrder" min="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('categorySortOrder') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model="categoryIsActive" id="categoryIsActive" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="categoryIsActive" class="ml-2 block text-sm text-gray-700">활성</label>
                                    </div>
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

    <!-- 기타 편집/삭제 모달들은 유사한 패턴으로 생략... -->
</div>