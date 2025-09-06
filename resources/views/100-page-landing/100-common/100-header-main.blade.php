{{-- 랜딩 페이지 헤더 --}}
<div class="bg-white shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- 로고 --}}
            <div class="flex items-center">
                <h1 class="text-2xl font-bold text-gray-900">Plobin</h1>
            </div>
            
            {{-- 네비게이션 메뉴 --}}
            <div class="hidden md:block">
                <div class="flex items-center gap-8">
                    <a href="/login" class="text-gray-600 hover:text-gray-900">로그인</a>
                    <a href="/signup" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">회원가입</a>
                </div>
            </div>
            
            {{-- 모바일 메뉴 --}}
            <div class="md:hidden">
                <button class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>