<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => 'Blade 생성기'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')

    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Blade 생성기</h1>
            <p class="text-gray-600 mb-8">Blade 템플릿을 생성하고 미리보기</p>

            @livewire('sandbox.blade-creator')
        </div>
    </div>
    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>
