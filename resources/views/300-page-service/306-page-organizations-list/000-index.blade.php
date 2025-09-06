{{-- 조직 목록 페이지 --}}
<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '조직 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.306-page-organizations-list.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include($common . '.100-header-main')
            @include(getCurrentViewPath())
        </div>
    </div>

    {{-- 조직 생성 모달 --}}
    @include('300-page-service.306-page-organizations-list.300-modal-create-organization')
    
    {{-- 조직 생성 완료 모달 --}}
    @include('300-page-service.306-page-organizations-list.301-modal-create-success')
    
    {{-- 조직 관리 모달 --}}
    @include('300-page-service.306-page-organizations-list.302-modal-organization-manager')

    {{-- 조직 모달 관리 시스템 --}}
    @include('300-page-service.306-page-organizations-list.403-js-modal-manager')
    {{-- 인증 관련 공통 함수들 --}}
    @include('300-page-service.300-common.002-auth-logout-handler')
    {{-- Alpine.js 초기화 --}}
    @include($common . '.900-alpine-init')
</body>
</html>
