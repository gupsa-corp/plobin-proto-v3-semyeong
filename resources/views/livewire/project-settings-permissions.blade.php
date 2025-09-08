<!-- 권한 관리 Livewire 컴포넌트 -->
<div>
    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.53a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('message') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- 커스텀 역할 관리 버튼들 -->
    <div class="mb-6 flex space-x-3">
        <button wire:click="$toggle('showCreateRoleForm')" 
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            커스텀 역할 생성
        </button>

        <button wire:click="$toggle('showRoleSelector')" 
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
            </svg>
            커스텀 역할 선택
        </button>
    </div>

    <!-- 커스텀 역할 생성 폼 -->
    @if($showCreateRoleForm)
        <div class="mb-6">
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">커스텀 역할 생성</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        프로젝트에 특화된 커스텀 역할을 생성하고 권한을 설정할 수 있습니다.
                    </p>
                    
                    <form wire:submit.prevent="createCustomRole">
                        <div class="mt-6">
                            <label for="role-name" class="block text-sm font-medium text-gray-700">역할 이름</label>
                            <div class="mt-1">
                                <input type="text" 
                                       wire:model="newRoleName"
                                       id="role-name" 
                                       class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" 
                                       placeholder="예: QA 담당자, 디자이너">
                                @error('newRoleName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700">권한 선택</label>
                            <div class="mt-2 space-y-2">
                                @foreach($this->availablePermissions as $permission => $label)
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                               wire:model="selectedPermissions" 
                                               value="{{ $permission }}"
                                               id="perm-{{ $permission }}" 
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="perm-{{ $permission }}" class="ml-3 text-sm text-gray-700">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <button wire:click="$set('showCreateRoleForm', false)" 
                                    type="button" 
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                취소
                            </button>
                            <button type="submit" 
                                    class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                역할 생성
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- 커스텀 역할 선택 모달 -->
    @if($showRoleSelector)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" 
             wire:click="$set('showRoleSelector', false)"></div>

        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div>
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-100">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-5">
                            <h3 class="text-base font-semibold leading-6 text-gray-900">커스텀 역할 선택</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">사용할 커스텀 역할을 선택하세요.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="space-y-4">
                            @forelse($this->customRoles as $role)
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none {{ $selectedRole == $role['id'] ? 'border-indigo-600 ring-2 ring-indigo-600' : 'border-gray-300' }}">
                                    <input type="radio" 
                                           wire:model="selectedRole" 
                                           value="{{ $role['id'] }}" 
                                           class="sr-only">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-sm font-medium text-gray-900">{{ $role['name'] }}</span>
                                            <span class="mt-1 flex items-center text-sm text-gray-500">
                                                {{ implode(', ', $role['permissions']) }}
                                            </span>
                                        </span>
                                    </span>
                                    @if($selectedRole == $role['id'])
                                        <svg class="h-5 w-5 text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.236 4.53L7.53 10.53a.75.75 0 00-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </label>
                            @empty
                                <div class="text-center py-6">
                                    <p class="text-sm text-gray-500">생성된 커스텀 역할이 없습니다.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                        <button type="button" 
                                wire:click="selectRole({{ $selectedRole }})"
                                class="inline-flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 sm:col-start-2"
                                {{ !$selectedRole ? 'disabled' : '' }}>
                            선택
                        </button>
                        <button type="button" 
                                wire:click="$set('showRoleSelector', false)"
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:col-start-1 sm:mt-0">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 선택된 역할 표시 -->
    @if($selectedRole)
        @php
            $selectedRoleData = $this->customRoles->firstWhere('id', $selectedRole);
        @endphp
        @if($selectedRoleData)
            <div class="mb-6">
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-indigo-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-indigo-800">
                                <strong>선택된 역할:</strong> {{ $selectedRoleData['name'] }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
</div>