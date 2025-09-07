<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => 'Git 버전 관리'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">Git 버전 관리</h1>
            <p class="text-gray-600 mb-8">로컬 Git 저장소 관리 도구</p>
            
            <div class="text-center py-20 text-gray-500">
                구현필요
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>