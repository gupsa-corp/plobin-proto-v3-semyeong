<div class="bg-white shadow rounded-lg">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">비밀번호 변경</h3>
    </div>
    <div class="p-6">
        @if (session('password_message'))
            <div class="mb-4 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('password_message') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form wire:submit.prevent="changePassword" class="space-y-4">
            <div>
                <label for="current-password" class="block text-sm font-medium text-gray-700 mb-2">현재 비밀번호 <span class="text-red-500">*</span></label>
                <input type="password" 
                       wire:model="current_password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       id="current-password" 
                       required>
                @error('current_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="new-password" class="block text-sm font-medium text-gray-700 mb-2">새 비밀번호 <span class="text-red-500">*</span></label>
                <input type="password" 
                       wire:model="new_password"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       id="new-password" 
                       required>
                <p class="text-xs text-gray-500 mt-1">최소 8자 이상, 영문, 숫자, 특수문자 조합</p>
                @error('new_password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label for="confirm-password" class="block text-sm font-medium text-gray-700 mb-2">비밀번호 확인 <span class="text-red-500">*</span></label>
                <input type="password" 
                       wire:model="new_password_confirmation"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       id="confirm-password" 
                       required>
                @error('new_password_confirmation')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="pt-2">
                <button type="submit" class="w-full bg-yellow-600 text-white px-4 py-2 rounded-md hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2">비밀번호 변경</button>
            </div>
        </form>
    </div>
</div>