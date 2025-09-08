<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include('700-page-sandbox.700-common.301-layout-head', ['title' => '시나리오 관리자'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen w-full">
        <div class="p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">📋 시나리오 관리자</h1>
            <p class="text-gray-600 mb-8">개발 시나리오와 요구사항을 체계적으로 관리하세요</p>
            @livewire('sandbox.scenario-manager')
        </div>
    </div>
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Filament Scripts -->
    @filamentScripts
</body>
</html>