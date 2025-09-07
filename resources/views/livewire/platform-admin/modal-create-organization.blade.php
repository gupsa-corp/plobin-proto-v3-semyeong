{{--
===========================================
개발 가이드라인 (DEVELOPMENT GUIDELINES)
===========================================

⚠️ 중요: 이 프로젝트에서는 순수 JavaScript 사용을 금지합니다
❌ 사용 금지: Vanilla JS, jQuery, Alpine.js의 복잡한 로직
✅ 사용 필수: Livewire + Filament 조합만 사용

모든 상호작용과 동적 기능은 다음으로만 구현:
- Livewire: 서버사이드 상태관리, 이벤트 처리
- Filament: UI 컴포넌트, 폼, 테이블 등
- 간단한 Alpine.js: 토글, 드롭다운 등 최소한의 UI 상호작용만

JavaScript가 필요한 경우 → Livewire로 재작성 필수
복잡한 UI가 필요한 경우 → Filament 컴포넌트 사용

===========================================
--}}

{{-- 조직 생성 모달 --}}
@if($showCreateModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-5 text-center">새 조직 추가</h3>
                
                <form wire:submit.prevent="createOrganization" class="mt-5">
                    <div class="space-y-4">
                        {{-- 조직 소유자 선택 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">조직 소유자 *</label>
                            
                            @if($selectedUser)
                                {{-- 선택된 사용자 표시 --}}
                                <div class="mt-1 flex items-center justify-between p-3 border border-green-300 bg-green-50 rounded-md">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">
                                                {{ substr($selectedUser->display_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900">{{ $selectedUser->display_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $selectedUser->email }}</p>
                                        </div>
                                    </div>
                                    <button 
                                        type="button"
                                        wire:click="clearSelectedUser"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            @else
                                {{-- 사용자 검색 (Livewire로 변경됨) --}}
                                <div class="mt-1 relative">
                                    <input 
                                        type="text" 
                                        wire:model.debounce.300ms="userSearchQuery"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                        placeholder="사용자 이름 또는 이메일로 검색..."
                                    >
                                    
                                    @if($showSearchResults && count($searchResults) > 0)
                                        <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                            @foreach($searchResults as $user)
                                                <div 
                                                    wire:click="selectSearchedUser({{ $user->id }})"
                                                    class="flex items-center p-3 hover:bg-gray-50 cursor-pointer">
                                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">{{ substr($user->display_name, 0, 1) }}</span>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm font-medium text-gray-900">{{ $user->display_name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                            
                            @error('selectedUser') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        {{-- 조직명 --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">조직명 *</label>
                            <input 
                                type="text" 
                                id="name"
                                wire:model="name"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="조직명을 입력하세요"
                                maxlength="25"
                            >
                            @error('name') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        {{-- URL --}}
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                            <input 
                                type="text" 
                                id="url"
                                wire:model="url"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="organization-url"
                                maxlength="50"
                            >
                            @error('url') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>

                        {{-- 설명 --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">설명</label>
                            <textarea 
                                id="description"
                                wire:model="description"
                                rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="조직에 대한 설명을 입력하세요">
                            </textarea>
                            @error('description') 
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button"
                            wire:click="closeCreateModal"
                            class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-md shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
                            취소
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            조직 생성
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif