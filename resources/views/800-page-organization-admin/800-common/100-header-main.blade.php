{{-- 조직 관리 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 섹션 --}}
            <div class="flex items-center gap-4">
                {{-- 대시보드 로고 링크 --}}
                <div>
                    <a href="/dashboard" class="inline-flex items-center text-gray-500 hover:text-gray-700 transition-colors">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0">
                            <rect width="32" height="32" rx="16" fill="black"></rect>
                            <path d="M9.467 6.068c0.621-1.082 2.394-1.082 3.014 0 0.628 1.094 1.408 2.268 2.255 3.115 0.847 0.847 2.021 1.627 3.115 2.255 1.082 0.621 1.082 2.394 0 3.014-1.094 0.628-2.268 1.408-3.115 2.255-0.847 0.847-1.627 2.021-2.255 3.115-0.621 1.082-2.394 1.082-3.014 0-0.628-1.094-1.408-2.268-2.255-3.115-0.847-0.847-2.021-1.627-3.115-2.255-1.082-0.621-1.082-2.394 0-3.014 1.094-0.628 2.268-1.408 3.115-2.255 0.847-0.847 1.627-2.021 2.255-3.115Z" fill="url(#paint0_linear_2_26)"></path>
                            <defs>
                                <linearGradient id="paint0_linear_2_26" x1="10.974" y1="22.249" x2="10.875" y2="2.747" gradientUnits="userSpaceOnUse">
                                    <stop></stop>
                                    <stop offset="1" stop-color="#0A82EC"></stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </a>
                </div>

                {{-- 브레드크럼 --}}
                <nav class="flex items-center text-sm">
                    <a href="/dashboard" class="text-gray-500 hover:text-gray-700 transition-colors">
                        대시보드
                    </a>
                    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <span class="text-gray-900 font-medium">조직 관리</span>
                </nav>
            </div>

            {{-- 페이지 타이틀 --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">조직 관리</h1>
                <p class="text-sm text-gray-500 mt-1">조직의 구성원, 권한, 결제 및 프로젝트를 관리합니다</p>
            </div>

            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- Livewire 사용자 드롭다운 컴포넌트 --}}
                <livewire:service.header.user-dropdown-livewire />
            </div>
        </div>
    </div>
</div>
