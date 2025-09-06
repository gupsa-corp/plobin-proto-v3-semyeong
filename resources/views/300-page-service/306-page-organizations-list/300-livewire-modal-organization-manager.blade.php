{{-- Organization Manager Modal for Dashboard --}}
@if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:transition>
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" wire:click="closeModal"></div>
        
        {{-- Modal Content --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-auto" wire:click.stop>
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">조직 생성</h3>
                        <button 
                            wire:click="closeModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                {{-- Body --}}
                <div class="px-6 py-4">
                    <form wire:submit.prevent="createOrganization">
                        <div class="space-y-4">
                            <div>
                                <label for="org_name" class="block text-sm font-medium text-gray-700 mb-1">
                                    조직명 *
                                </label>
                                <input 
                                    type="text" 
                                    id="org_name" 
                                    wire:model="name"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="조직명을 입력하세요"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="org_description" class="block text-sm font-medium text-gray-700 mb-1">
                                    조직 설명
                                </label>
                                <textarea 
                                    id="org_description" 
                                    wire:model="description"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="조직에 대한 간단한 설명을 입력하세요"
                                ></textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Footer --}}
                        <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                            >
                                취소
                            </button>
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span wire:loading.remove>생성</span>
                                <span wire:loading class="flex items-center">
                                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    생성 중...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif