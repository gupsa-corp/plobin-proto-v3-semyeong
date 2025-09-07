<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI 샌드박스 - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- 헤더 -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4">
                    <h1 class="text-2xl font-bold text-gray-900">AI 샌드박스</h1>
                    <p class="text-gray-600 mt-1">AI가 사용할 수 있는 샌드박스 환경</p>
                </div>
            </div>

            <!-- 네비게이션 -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4">
                    <nav class="flex space-x-8">
                        <a href="{{ route('sandbox.file-manager') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sandbox.file-manager*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900' }}">
                            파일 관리
                        </a>
                        <a href="{{ route('sandbox.sql-executor') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sandbox.sql-executor*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900' }}">
                            SQL 실행
                        </a>
                        <a href="{{ route('sandbox.table-manager') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sandbox.table-manager*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900' }}">
                            테이블 관리
                        </a>
                        <a href="{{ route('sandbox.code-executor') }}"
                           class="px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('sandbox.code-executor*') ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:text-gray-900' }}">
                            코드 실행
                        </a>
                    </nav>
                </div>
            </div>

            <!-- 메인 콘텐츠 -->
            <div class="bg-white shadow rounded-lg">
                <div class="p-6">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @livewireScripts
</body>
</html>
