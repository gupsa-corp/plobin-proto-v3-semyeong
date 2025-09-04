{{-- 서비스 공통 사이드바 구조 --}}
<nav class="service-sidebar w-64">
    <!-- 로고 -->
    <div class="p-6 border-b border-gray-200">
        <a href="/dashboard" class="flex items-center">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-lg">P</span>
            </div>
            <span class="ml-3 text-xl font-bold text-gray-900">Plobin</span>
        </a>
    </div>

    <!-- 메뉴 -->
    <div class="p-4">
        <ul>
            @php
                // 글로벌 변수에서 메뉴 아이템 가져오기 (전달된 변수가 없을 경우 대비)
                $items = $menuItems ?? $GLOBALS['menuItems'] ?? [];
            @endphp
            @if(count($items) > 0)
                @foreach($items as $item)
                    <li>
                        <a href="{{ $item['url'] }}"
                           class="service-nav-item {{ $item['active'] ? 'active' : '' }}">
                            {{ $item['title'] }}
                        </a>
                    </li>
                @endforeach
            @endif
        </ul>
    </div>
</nav>
