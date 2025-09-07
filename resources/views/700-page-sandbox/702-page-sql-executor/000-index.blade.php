<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => 'SQL 실행기'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen w-full">
        @livewire('sandbox.sql-executor.component')
    </div>
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>