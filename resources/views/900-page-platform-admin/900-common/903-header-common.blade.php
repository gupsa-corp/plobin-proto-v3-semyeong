{{-- 공통 플랫폼 관리자 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $pageTitle ?? '페이지' }}</h1>
                @if(isset($pageDescription))
                <p class="text-sm text-gray-600 mt-1">{{ $pageDescription }}</p>
                @endif
            </div>
            
            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- 플랫폼 관리자 표시 --}}
                <div class="flex items-center px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                    </svg>
                    플랫폼 관리자
                </div>
                
                {{-- 사용자 정보 --}}
                <div class="flex items-center">
                    <div class="text-sm text-right mr-3">
                        <div class="text-gray-900 font-medium">{{ auth()->check() ? auth()->user()->name : '게스트 사용자' }}</div>
                        <div class="text-gray-500">{{ auth()->check() ? auth()->user()->email : 'guest@example.com' }}</div>
                    </div>
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">{{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'G' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>