<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '파일 매니저'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <div class="border-b px-6 py-4">
                <h2 class="text-xl font-semibold text-gray-800">파일 매니저</h2>
                <p class="text-sm text-gray-600 mt-1">파일 업로드, 관리, 다운로드를 위한 통합 파일 시스템</p>
            </div>
            
            <div class="p-6">
                @livewire('sandbox.file-manager')
            </div>
        </div>
    </div>
    
    <!-- Livewire Scripts (must load first for $wire) -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
    
    <!-- Filemanager Scripts -->
    @filemanagerScripts
</body>
</html>