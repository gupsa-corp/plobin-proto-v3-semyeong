<!-- 사용자 메뉴 (Alpine.js) -->
<div x-data="{
    open: false,
    logout() {
        fetch('/api/auth/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            credentials: 'include'
        })
        .then(response => {
            window.location.href = '/login';
        })
    }
}" class="relative">
    <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50">
        <div class="h-8 w-8 bg-primary-500 rounded-full flex items-center justify-center text-white text-sm font-medium">
            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
        </div>
        <span class="hidden md:block text-sm font-medium text-gray-700">사용자</span>
        <svg class="h-4 w-4 text-gray-400 transition-transform duration-200"
             :class="{ 'rotate-180': open }"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- 드롭다운 메뉴 -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.outside="open = false"
         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
        <a href="/mypage" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">프로필</a>
        <div class="border-t border-gray-100"></div>
        <button @click="logout()" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">로그아웃</button>
    </div>
</div>
