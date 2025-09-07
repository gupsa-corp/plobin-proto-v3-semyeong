{{-- 플랫폼 관리자 사이드바 --}}
<nav class="sidebar" style="position: fixed; left: 0; top: 0; width: 240px; height: 100vh; background: #ffffff; border-right: 1px solid #E1E1E4; display: flex; flex-direction: column; z-index: 10; box-sizing: border-box;">
    플랫폼 관리자
    @include('000-common-assets.100-logo')
    {{-- 플랫폼 관리자 정보 --}}
    <div style="padding: 16px; border-bottom: 1px solid #E1E1E4;">
        <div class="text-sm text-gray-600 mb-1">플랫폼 관리자</div>
        <div class="font-medium text-gray-900">{{ auth()->check() ? auth()->user()->name : '게스트 사용자' }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ auth()->check() ? auth()->user()->email : 'guest@example.com' }}</div>
    </div>

    {{-- 네비게이션 메뉴 --}}
    <div style="flex: 1; overflow-y: auto; padding: 16px 0;">
        <ul class="space-y-2">
            <li>
                <a href="{{ route('platform.admin.dashboard') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-2 {{ request()->routeIs('platform.admin.dashboard') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                    대시보드
                </a>
            </li>
            <li>
                <a href="{{ route('platform.admin.organizations') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-2 {{ request()->routeIs('platform.admin.organizations*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    조직 관리
                </a>
            </li>
            <li>
                <a href="{{ route('platform.admin.users') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-2 {{ request()->routeIs('platform.admin.users*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    사용자 관리
                </a>
            </li>
            <li>
                <a href="{{ route('platform.admin.permissions') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-2 {{ request()->routeIs('platform.admin.permissions*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                    </svg>
                    권한 관리
                </a>
            </li>
            <li>
                <a href="{{ route('platform.admin.system-settings') }}"
                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md mx-2 {{ request()->routeIs('platform.admin.system-settings*') ? 'bg-blue-50 text-blue-700' : '' }}">
                    <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                    </svg>
                    시스템 설정
                </a>
            </li>
        </ul>
    </div>

    {{-- 하단 메뉴 --}}
    <div style="border-top: 1px solid #E1E1E4; padding: 16px;">
        <a href="{{ route('dashboard') }}"
           class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
            <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
            </svg>
            서비스로 돌아가기
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf
            <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-md">
                <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                </svg>
                로그아웃
            </button>
        </form>
    </div>
</nav>
