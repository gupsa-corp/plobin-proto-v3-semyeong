{{-- 플랫폼 관리자 - 역할 관리 메인 컴포넌트 --}}
<div class="space-y-6" x-data="{
    showToast: false,
    toastMessage: '',
    toastType: 'success'
}">
    {{-- 헤더 섹션 --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold text-gray-900">역할 관리</h2>
            <p class="text-sm text-gray-600 mt-1">플랫폼의 역할과 권한을 관리합니다.</p>
        </div>
        <button wire:click="openCreateModal" 
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
            + 새 역할 생성
        </button>
    </div>

    {{-- 역할 목록 테이블 --}}
    <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">등록된 역할</h3>
            <p class="text-sm text-gray-500 mt-1">총 {{ count($roles) }}개의 역할이 등록되어 있습니다.</p>
        </div>
        
        @if(count($roles) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                역할
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                권한 수
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                사용자 수
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                생성일
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                작업
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($roles as $role)
                            <tr class="hover:bg-gray-50 cursor-pointer" wire:click="selectRole({{ $role['id'] }})">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $role['display_info']['color'] }}-100 text-{{ $role['display_info']['color'] }}-800">
                                            {{ $role['display_info']['label'] }}
                                        </span>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $role['name'] }}</div>
                                            <div class="text-sm text-gray-500">{{ $role['display_info']['description'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $role['permissions_count'] }}개
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $role['users_count'] }}명
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ date('Y-m-d', strtotime($role['created_at'])) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    <button wire:click.stop="openEditModal({{ $role['id'] }})" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        편집
                                    </button>
                                    @if($role['users_count'] == 0)
                                        <button wire:click.stop="openDeleteModal({{ $role['id'] }})" 
                                                class="text-red-600 hover:text-red-900">
                                            삭제
                                        </button>
                                    @else
                                        <span class="text-gray-400">삭제불가</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <div class="text-gray-500">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">역할이 없습니다</h3>
                    <p class="mt-1 text-sm text-gray-500">새로운 역할을 생성하여 권한을 관리해보세요.</p>
                </div>
            </div>
        @endif
    </div>

    {{-- 선택된 역할 상세 정보 --}}
    @if($selectedRole)
        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $selectedRole['display_info']['color'] }}-100 text-{{ $selectedRole['display_info']['color'] }}-800">
                            {{ $selectedRole['display_info']['label'] }}
                        </span>
                        <h3 class="text-lg font-medium text-gray-900">{{ $selectedRole['name'] }}</h3>
                    </div>
                    <div class="text-sm text-gray-500">
                        사용자 {{ $selectedRole['users_count'] }}명이 이 역할을 사용중
                    </div>
                </div>
                <p class="text-sm text-gray-600 mt-2">{{ $selectedRole['display_info']['description'] }}</p>
            </div>
            
            <div class="px-6 py-4">
                <h4 class="text-sm font-medium text-gray-900 mb-3">할당된 권한 ({{ count($selectedRole['permissions']) }}개)</h4>
                @if(count($selectedRole['permissions']) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($selectedRole['permissions'] as $permission)
                            <span class="inline-flex items-center px-2.5 py-1.5 rounded-md text-xs font-medium bg-green-100 text-green-800">
                                {{ $permission }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500">할당된 권한이 없습니다.</p>
                @endif
            </div>
        </div>
    @endif

    {{-- 역할 생성 모달 --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" x-show="true">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                        <div class="bg-white px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">새 역할 생성</h3>
                                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="createRole" class="space-y-6">
                            <div class="px-6 py-4 space-y-6">
                                {{-- 기본 정보 --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">역할명 *</label>
                                        <input type="text" 
                                               wire:model="name" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                               placeholder="예: project_manager">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Guard Name</label>
                                        <select wire:model="guard_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="web">web</option>
                                            <option value="api">api</option>
                                        </select>
                                        @error('guard_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- 권한 선택 --}}
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-medium text-gray-700">권한 선택</label>
                                        <button type="button" 
                                                @click="
                                                    const checkboxes = document.querySelectorAll('input[type=checkbox][wire\\:model=selectedPermissions]');
                                                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                                                    checkboxes.forEach(cb => {
                                                        if (allChecked) {
                                                            cb.checked = false;
                                                            cb.dispatchEvent(new Event('input'));
                                                        } else {
                                                            cb.checked = true;
                                                            cb.dispatchEvent(new Event('input'));
                                                        }
                                                    });
                                                "
                                                class="text-sm text-blue-600 hover:text-blue-800">
                                            전체 선택/해제
                                        </button>
                                    </div>
                                    
                                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4">
                                        @foreach($permissions as $category => $categoryPermissions)
                                            <div class="mb-4 last:mb-0">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2 border-b border-gray-100 pb-1">{{ $category }}</h4>
                                                <div class="space-y-2">
                                                    @foreach($categoryPermissions as $permission)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   wire:model="selectedPermissions" 
                                                                   value="{{ $permission->name }}"
                                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                                <button type="button" 
                                        wire:click="closeModals" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    취소
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                    생성하기
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 역할 편집 모달 --}}
    @if($showEditModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" x-show="true">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-4xl">
                        <div class="bg-white px-6 py-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-medium text-gray-900">역할 편집: {{ $name }}</h3>
                                <button wire:click="closeModals" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <form wire:submit.prevent="updateRole" class="space-y-6">
                            <div class="px-6 py-4 space-y-6">
                                {{-- 기본 정보 --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">역할명 *</label>
                                        <input type="text" 
                                               wire:model="name" 
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Guard Name</label>
                                        <select wire:model="guard_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                            <option value="web">web</option>
                                            <option value="api">api</option>
                                        </select>
                                        @error('guard_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                {{-- 권한 선택 --}}
                                <div>
                                    <div class="flex items-center justify-between mb-4">
                                        <label class="block text-sm font-medium text-gray-700">권한 선택</label>
                                        <button type="button" 
                                                @click="
                                                    const checkboxes = document.querySelectorAll('input[type=checkbox][wire\\:model=selectedPermissions]');
                                                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                                                    checkboxes.forEach(cb => {
                                                        if (allChecked) {
                                                            cb.checked = false;
                                                            cb.dispatchEvent(new Event('input'));
                                                        } else {
                                                            cb.checked = true;
                                                            cb.dispatchEvent(new Event('input'));
                                                        }
                                                    });
                                                "
                                                class="text-sm text-blue-600 hover:text-blue-800">
                                            전체 선택/해제
                                        </button>
                                    </div>
                                    
                                    <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-md p-4">
                                        @foreach($permissions as $category => $categoryPermissions)
                                            <div class="mb-4 last:mb-0">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2 border-b border-gray-100 pb-1">{{ $category }}</h4>
                                                <div class="space-y-2">
                                                    @foreach($categoryPermissions as $permission)
                                                        <label class="flex items-center">
                                                            <input type="checkbox" 
                                                                   wire:model="selectedPermissions" 
                                                                   value="{{ $permission->name }}"
                                                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                                            <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                                <button type="button" 
                                        wire:click="closeModals" 
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                    취소
                                </button>
                                <button type="submit" 
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700">
                                    수정하기
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 역할 삭제 모달 --}}
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" x-show="true">
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-6 py-4">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.962-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900">역할 삭제 확인</h3>
                                    @if($editingRole)
                                        <div class="mt-2 text-sm text-gray-500">
                                            <p>정말로 <strong>{{ $editingRole->name }}</strong> 역할을 삭제하시겠습니까?</p>
                                            <p class="mt-2 text-red-600">이 작업은 되돌릴 수 없습니다.</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                            <button wire:click="closeModals" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                취소
                            </button>
                            <button wire:click="deleteRole" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700">
                                삭제하기
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- 토스트 알림 --}}
    <div x-show="showToast" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         class="fixed top-4 right-4 z-50 max-w-sm w-full">
        <div :class="{
            'bg-green-50 border-green-200 text-green-800': toastType === 'success',
            'bg-red-50 border-red-200 text-red-800': toastType === 'error'
        }" class="border rounded-lg shadow-lg p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div :class="{
                        'text-green-400': toastType === 'success',
                        'text-red-400': toastType === 'error'
                    }">
                        <svg x-show="toastType === 'success'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <svg x-show="toastType === 'error'" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <p class="text-sm font-medium" x-text="toastMessage"></p>
                </div>
                <button @click="showToast = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript for toast notifications --}}
<script>
document.addEventListener('livewire:initialized', function () {
    Livewire.on('notification', function (data) {
        const component = Alpine.$data(document.querySelector('[x-data*="showToast"]'));
        if (component) {
            component.toastType = data.type || 'success';
            component.toastMessage = data.message || '';
            component.showToast = true;
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                component.showToast = false;
            }, 5000);
        }
    });
});
</script>