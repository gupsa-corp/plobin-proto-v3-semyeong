<form wire:submit.prevent="login" class="space-y-4">
    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            이메일
        </label>
        <input 
            type="email" 
            id="email"
            name="email"
            wire:model="email"
            autocomplete="email"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="이메일을 입력하세요"
        />
        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
            비밀번호
        </label>
        <input 
            type="password" 
            id="password"
            name="password"
            wire:model="password"
            autocomplete="current-password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="비밀번호를 입력하세요"
        />
        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="flex items-center">
        <input 
            type="checkbox" 
            id="remember"
            wire:model="remember"
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        />
        <label for="remember" class="ml-2 block text-sm text-gray-900">
            로그인 상태 유지
        </label>
    </div>

    <button 
        type="submit" 
        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
    >
        <span wire:loading.remove>로그인</span>
        <span wire:loading>로그인 중...</span>
    </button>
</form>
