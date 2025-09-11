<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '커스텀 화면'])

<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.401-custom-screens-header')
    
    <div class="min-h-screen">
        <div class="w-full">
            @livewire('sandbox.custom-screens.browser.component')
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>