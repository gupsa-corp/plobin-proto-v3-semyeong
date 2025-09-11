<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '실시간 프로젝트 관리 - 샌드박스'])

<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.401-custom-screens-header')
    
    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <!-- 브레드크럼 -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('sandbox.custom-screens') }}" class="text-gray-500 hover:text-blue-600">
                                🎨 템플릿 화면 관리
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <span class="text-gray-400 mx-2">/</span>
                                <span class="text-gray-900 font-medium">실시간 프로젝트 관리</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- 헤더 -->
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-green-600 text-lg">📋</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-green-900">실시간 프로젝트 관리</h1>
                            <p class="text-green-700 text-sm">샌드박스 데이터베이스의 모든 프로젝트를 실시간으로 관리합니다.</p>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('sandbox.custom-screens') }}" 
                           class="px-3 py-1.5 text-sm bg-white text-green-600 border border-green-300 rounded hover:bg-green-50">
                            ← 목록으로
                        </a>
                        <button onclick="window.location.reload()" 
                                class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700">
                            🔄 새로고침
                        </button>
                    </div>
                </div>
            </div>

            <!-- Livewire 컴포넌트 -->
            @livewire('sandbox.custom-screens.live.project-list-component')
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>