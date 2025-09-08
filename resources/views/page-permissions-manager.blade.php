<div>
    <!-- 성공 메시지 -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- 현재 권한 상태 -->
    <div class="mb-6">
        <h3 class="text-sm font-medium text-gray-700 mb-3">현재 권한 설정</h3>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
            {{ $this->accessLevelLabel }}
        </div>
        <p class="text-sm text-gray-500 mt-2">{{ $this->accessLevelDescription }}</p>
    </div>

    <form wire:submit.prevent="updatePermissions" class="space-y-6">
        <!-- 접근 권한 설정 -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-3">
                페이지 접근 권한
            </label>
            
            <div class="space-y-3">
                <!-- 모든 사용자 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="accessLevel"
                        type="radio" 
                        id="access_public" 
                        value="public" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="access_public" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="font-medium text-gray-900">모든 사용자</div>
                        </div>
                        <div class="text-sm text-gray-500">누구나 이 페이지를 볼 수 있습니다.</div>
                    </label>
                </div>

                <!-- 조직 멤버만 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="accessLevel"
                        type="radio" 
                        id="access_members" 
                        value="members_only" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="access_members" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div class="font-medium text-gray-900">조직 멤버만</div>
                        </div>
                        <div class="text-sm text-gray-500">조직에 속한 멤버만 볼 수 있습니다.</div>
                    </label>
                </div>

                <!-- 편집자 이상 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="accessLevel"
                        type="radio" 
                        id="access_editors" 
                        value="editors_only" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="access_editors" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            <div class="font-medium text-gray-900">편집자 이상</div>
                        </div>
                        <div class="text-sm text-gray-500">편집 권한 이상을 가진 사용자만 볼 수 있습니다.</div>
                    </label>
                </div>

                <!-- 관리자만 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="accessLevel"
                        type="radio" 
                        id="access_admins" 
                        value="admins_only" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="access_admins" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            <div class="font-medium text-gray-900">관리자만</div>
                        </div>
                        <div class="text-sm text-gray-500">관리자 권한을 가진 사용자만 볼 수 있습니다.</div>
                    </label>
                </div>

                <!-- 사용자 지정 -->
                <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                    <input 
                        wire:model="accessLevel"
                        type="radio" 
                        id="access_custom" 
                        value="custom" 
                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500"
                    >
                    <label for="access_custom" class="ml-3 flex-1">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div class="font-medium text-gray-900">사용자 지정</div>
                        </div>
                        <div class="text-sm text-gray-500">선택된 특정 사용자만 볼 수 있습니다.</div>
                    </label>
                </div>
            </div>
            @error('accessLevel') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <!-- 사용자 지정 권한 설정 -->
        @if($accessLevel === 'custom')
        <div class="border border-gray-200 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-3">접근 허용 사용자 선택</h4>
            
            @if(count($organizationMembers) > 0)
            <div class="space-y-2 max-h-60 overflow-y-auto">
                @foreach($organizationMembers as $member)
                <div class="flex items-center">
                    <input 
                        wire:model="allowedRoles"
                        type="checkbox" 
                        id="user_{{ $member->user->id }}" 
                        value="{{ $member->user->id }}"
                        class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                    >
                    <label for="user_{{ $member->user->id }}" class="ml-3 flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-gray-700">{{ substr($member->user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">{{ $member->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $member->user->email }}</div>
                        </div>
                    </label>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-sm text-gray-500">조직 멤버가 없습니다.</p>
            @endif
        </div>
        @endif

        <!-- 저장 버튼 -->
        <div class="flex justify-end space-x-3 pt-4">
            <button 
                type="button"
                onclick="window.history.back()"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
                취소
            </button>
            <button 
                type="submit"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>권한 저장</span>
                <span wire:loading>저장 중...</span>
            </button>
        </div>
    </form>
</div>