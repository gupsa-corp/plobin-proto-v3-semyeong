{{-- 프로필 페이지 헤더 --}}
<div class="bg-white border-b border-gray-200">
    <div class="px-6 py-4">
        <div class="flex items-center justify-between">
            {{-- 페이지 타이틀 --}}
            <div>
                <h1 class="text-xl font-semibold text-gray-900">조직 관리</h1>
            </div>
            
            {{-- 헤더 우측 메뉴 --}}
            <div class="flex items-center gap-4">
                {{-- 사용자 정보 --}}
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">{{ Auth::user()->name ?? '사용자' }}</div>
                        <div class="text-xs text-gray-500">{{ Auth::user()->email ?? '' }}</div>
                    </div>
                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-600">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>