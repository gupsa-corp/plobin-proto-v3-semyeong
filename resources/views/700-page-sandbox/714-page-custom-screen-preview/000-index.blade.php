<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => $screen['title'] . ' - 미리보기'])

<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- 미리보기 헤더 -->
        <div class="bg-white border-b border-gray-200 px-6 py-3">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button onclick="window.close()" class="text-gray-600 hover:text-gray-900">
                        ✕ 닫기
                    </button>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <h1 class="text-lg font-semibold text-gray-900">
                        📱 {{ $screen['title'] }} - 미리보기
                    </h1>
                    <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                        {{ ucfirst($screen['type']) }}
                    </span>
                </div>
                <div class="flex items-center space-x-3 text-sm text-gray-500">
                    <span>생성일: {{ $screen['created_at'] }}</span>
                    <a href="{{ route('sandbox.custom-screens') }}" 
                       target="_blank" 
                       class="text-blue-600 hover:text-blue-800">
                        편집하러 가기
                    </a>
                </div>
            </div>
        </div>
        
        <!-- 미리보기 내용 -->
        <div class="p-6">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                @if($screen['blade_template'] && $screen['livewire_component'])
                    @livewire('sandbox.custom-screens.renderer.component', ['screenData' => $screen])
                @else
                    <div class="p-8 text-center text-gray-500">
                        <div class="text-6xl mb-4">📱</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">미리보기를 사용할 수 없습니다</h3>
                        <p>블레이드 템플릿 또는 라이브와이어 컴포넌트가 없습니다.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
    
    <!-- 새 창 전용 스타일 -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        
        .preview-container {
            width: 100%;
            min-height: 100vh;
        }
        
        /* 미리보기에서 스크롤바 스타일 개선 */
        ::-webkit-scrollbar {
            width: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</body>
</html>