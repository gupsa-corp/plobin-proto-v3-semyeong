<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-lg font-medium text-gray-900">동적 권한 규칙 관리</h2>
        <button wire:click="openCreateModal" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            새 규칙 생성
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- 규칙 목록 -->
        <div class="lg:col-span-2">
            @if(count($rules) > 0)
                <div class="space-y-6">
                    @foreach($rules as $resourceType => $resourceRules)
                        <div>
                            <h3 class="text-md font-medium text-gray-800 mb-3">{{ $resourceType }}</h3>
                            <div class="space-y-2">
                                @foreach($resourceRules as $rule)
                                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow cursor-pointer"
                                         wire:click="selectRule({{ $rule['id'] }})">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center space-x-3">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $rule['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $rule['is_active'] ? '활성' : '비활성' }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        우선순위 {{ $rule['priority'] }}
                                                    </span>
                                                </div>
                                                <h4 class="text-sm font-medium text-gray-900 mt-2">{{ $rule['action'] }}</h4>
                                                @if($rule['description'])
                                                    <p class="text-sm text-gray-600 mt-1">{{ $rule['description'] }}</p>
                                                @endif
                                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                                    @if(count($rule['required_roles']) > 0)
                                                        <span>역할: {{ implode(', ', $rule['required_roles']) }}</span>
                                                    @endif
                                                    @if(count($rule['required_permissions']) > 0)
                                                        <span>권한: {{ implode(', ', $rule['required_permissions']) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <button wire:click.stop="toggleRuleStatus({{ $rule['id'] }})" 
                                                        class="text-gray-600 hover:text-gray-900" 
                                                        title="{{ $rule['is_active'] ? '비활성화' : '활성화' }}">
                                                    @if($rule['is_active'])
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                    @else
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                    @endif
                                                </button>
                                                <button wire:click.stop="openTestModal({{ $rule['id'] }})" 
                                                        class="text-blue-600 hover:text-blue-900" 
                                                        title="테스트">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click.stop="openEditModal({{ $rule['id'] }})" 
                                                        class="text-indigo-600 hover:text-indigo-900" 
                                                        title="편집">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button wire:click.stop="openDeleteModal({{ $rule['id'] }})" 
                                                        class="text-red-600 hover:text-red-900" 
                                                        title="삭제">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">동적 규칙 없음</h3>
                    <p class="mt-1 text-sm text-gray-500">새로운 동적 권한 규칙을 생성해보세요.</p>
                </div>
            @endif
        </div>

        <!-- 선택된 규칙 상세 -->
        <div class="lg:col-span-1">
            @if($selectedRule)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">규칙 상세 정보</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">리소스 타입</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['resource_type'] }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">액션</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['action'] }}</dd>
                        </div>
                        
                        @if($selectedRule['description'])
                            <div>
                                <dt class="text-sm font-medium text-gray-500">설명</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['description'] }}</dd>
                            </div>
                        @endif
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">상태</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $selectedRule['is_active'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $selectedRule['is_active'] ? '활성' : '비활성' }}
                                </span>
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">우선순위</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['priority'] }}</dd>
                        </div>
                        
                        @if(count($selectedRule['required_roles']) > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">필수 역할</dt>
                                <dd class="space-y-1">
                                    @foreach($selectedRule['required_roles'] as $role)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1 mb-1">
                                            {{ $role }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                        
                        @if(count($selectedRule['required_permissions']) > 0)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-2">필수 권한</dt>
                                <dd class="space-y-1">
                                    @foreach($selectedRule['required_permissions'] as $permission)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mr-1 mb-1">
                                            {{ $permission }}
                                        </span>
                                    @endforeach
                                </dd>
                            </div>
                        @endif
                        
                        @if($selectedRule['custom_logic'])
                            <div>
                                <dt class="text-sm font-medium text-gray-500">커스텀 로직</dt>
                                <dd class="mt-1">
                                    <pre class="text-xs bg-gray-100 p-2 rounded overflow-x-auto">{{ json_encode($selectedRule['custom_logic'], JSON_PRETTY_PRINT) }}</pre>
                                </dd>
                            </div>
                        @endif
                        
                        <div class="pt-4 border-t border-gray-200">
                            <dt class="text-sm font-medium text-gray-500">생성일</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['created_at']->format('Y-m-d H:i:s') }}</dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">수정일</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $selectedRule['updated_at']->format('Y-m-d H:i:s') }}</dd>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 rounded-lg p-6 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">규칙을 선택하세요</h3>
                    <p class="mt-1 text-sm text-gray-500">규칙을 클릭하면 상세 정보를 볼 수 있습니다.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- 규칙 생성 모달 -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <form wire:submit.prevent="createRule">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">새 동적 규칙 생성</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="resourceType" class="block text-sm font-medium text-gray-700">리소스 타입</label>
                                    <input type="text" wire:model="resourceType" id="resourceType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('resourceType') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label for="action" class="block text-sm font-medium text-gray-700">액션</label>
                                    <input type="text" wire:model="action" id="action" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @error('action') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700">설명</label>
                                    <textarea wire:model="description" id="description" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">필수 역할</label>
                                    @foreach($roles as $role)
                                        <label class="flex items-center mb-1">
                                            <input type="checkbox" wire:model="requiredRoles" value="{{ $role }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ $role }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">필수 권한</label>
                                    <div class="max-h-32 overflow-y-auto">
                                        @foreach($permissions as $permission)
                                            <label class="flex items-center mb-1">
                                                <input type="checkbox" wire:model="requiredPermissions" value="{{ $permission }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700">{{ $permission }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label for="customLogic" class="block text-sm font-medium text-gray-700">커스텀 로직 (JSON)</label>
                                    <textarea wire:model="customLogic" id="customLogic" rows="4" placeholder='{"condition": "and", "rules": []}' class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono"></textarea>
                                    @error('customLogic') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center space-x-4">
                                    <div>
                                        <label for="priority" class="block text-sm font-medium text-gray-700">우선순위</label>
                                        <input type="number" wire:model="priority" id="priority" min="0" max="999" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div class="flex items-center mt-6">
                                        <input type="checkbox" wire:model="isActive" id="isActive" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                        <label for="isActive" class="ml-2 block text-sm text-gray-700">활성</label>
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

    <!-- 테스트 모달 -->
    @if($showTestModal && $selectedRule)
        <div class="fixed inset-0 z-10 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModals"></div>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">권한 규칙 테스트</h3>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-3 rounded">
                                <h4 class="font-medium text-gray-800">테스트할 규칙</h4>
                                <p class="text-sm text-gray-600">{{ $selectedRule['resource_type'] }}.{{ $selectedRule['action'] }}</p>
                            </div>
                            
                            <div>
                                <label for="testUserId" class="block text-sm font-medium text-gray-700">테스트 사용자</label>
                                <select wire:model="testUserId" id="testUserId" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">사용자 선택</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label for="testContext" class="block text-sm font-medium text-gray-700">테스트 컨텍스트 (JSON)</label>
                                <textarea wire:model="testContext" id="testContext" rows="3" placeholder='{"key": "value"}' class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm font-mono"></textarea>
                            </div>
                            
                            @if($testResult)
                                <div class="bg-{{ $testResult['success'] ? 'green' : 'red' }}-50 border border-{{ $testResult['success'] ? 'green' : 'red' }}-200 rounded-md p-3">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            @if($testResult['success'])
                                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 10.414l1.707-1.707a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-{{ $testResult['success'] ? 'green' : 'red' }}-800">
                                                테스트 결과: {{ $testResult['success'] ? '성공' : '실패' }}
                                            </h3>
                                            <div class="mt-2 text-sm text-{{ $testResult['success'] ? 'green' : 'red' }}-700">
                                                <p>{{ $testResult['message'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button wire:click="testRule" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            테스트 실행
                        </button>
                        <button wire:click="closeModals" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            닫기
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- 다른 편집/삭제 모달들은 유사한 패턴으로 생략... -->
</div>