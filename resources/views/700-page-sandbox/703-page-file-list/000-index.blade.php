<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '파일 매니저'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">파일 매니저</h1>
            <p class="text-gray-600 mb-8">드래그 앤 드롭으로 파일을 관리하세요</p>
            
            <x-livewire-filemanager />
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
    
    <!-- Filemanager Scripts -->
    @filemanagerScripts
</body>
</html>