{{-- 공통 서비스 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $pageTitle ?? '페이지' }}</h1>
                {{-- 브레드크럼 --}}
                <nav class="flex items-center text-xs text-gray-500 mb-1">
                    <a href="/dashboard" class="hover:text-gray-700 transition-colors">
                        조직
                    </a>
                    <svg class="w-3 h-3 mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-700">{{ $pageTitle ?? '페이지' }}</span>
                </nav>
            </div>

            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>
</div>
