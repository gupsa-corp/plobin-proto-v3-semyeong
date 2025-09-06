<form wire:submit.prevent="register" class="space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                이름
            </label>
            <input 
                type="text" 
                id="first_name"
                wire:model="first_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="이름을 입력하세요"
            />
            @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                성
            </label>
            <input 
                type="text" 
                id="last_name"
                wire:model="last_name"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                placeholder="성을 입력하세요"
            />
            @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
            이메일
        </label>
        <input 
            type="email" 
            id="email"
            wire:model="email"
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
            wire:model="password"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="비밀번호를 입력하세요 (최소 6자)"
        />
        @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
            비밀번호 확인
        </label>
        <input 
            type="password" 
            id="password_confirmation"
            wire:model="password_confirmation"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="비밀번호를 다시 입력하세요"
        />
        @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <button 
        type="submit" 
        class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200"
        wire:loading.attr="disabled"
        wire:loading.class="opacity-50 cursor-not-allowed"
    >
        <span wire:loading.remove>회원가입</span>
        <span wire:loading>가입 중...</span>
    </button>
</form>
