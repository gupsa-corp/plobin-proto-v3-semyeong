<div>
    @if($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" wire:click.stop>
            <div class="mt-3">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0h-2m-2-5a2 2 0 100-4 2 2 0 000 4zm8 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="mt-5 text-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">비밀번호 확인</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 mb-4">
                            개인정보 수정을 위해 현재 비밀번호를 입력해주세요.
                        </p>
                        
                        <form wire:submit.prevent="verifyPassword">
                            <div class="mb-4">
                                <input 
                                    type="password" 
                                    wire:model="password" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    placeholder="현재 비밀번호"
                                    autofocus
                                >
                            </div>
                            
                            @if($errorMessage)
                            <div class="mb-4 text-red-600 text-sm">
                                {{ $errorMessage }}
                            </div>
                            @endif
                        </form>
                    </div>
                    
                    <div class="items-center px-4 py-3">
                        <button 
                            wire:click="verifyPassword"
                            class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 mr-2"
                        >
                            확인
                        </button>
                        <button 
                            wire:click="closeModal"
                            class="mt-2 px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300"
                        >
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>