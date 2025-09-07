<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => 'SQL 실행기'])
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')
    
    <div class="min-h-screen sandbox-container">
        <div class="sandbox-card">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">SQL 실행기</h1>
            <p class="text-gray-600 mb-8">데이터베이스 쿼리 실행 도구</p>
            
            @livewire('sandbox.sql-executor.component')
        </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>