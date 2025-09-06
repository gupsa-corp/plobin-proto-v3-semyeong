<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '회원 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('300-page-service.310-organization-admin.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('300-page-service.310-organization-admin.100-header-main')
            
            {{-- Livewire 멤버 관리 컴포넌트 --}}
            @livewire('test-component')
            <hr class="my-8">
            @livewire('organization.admin.member-management', ['organizationId' => $id ?? 1])
        </div>
    </div>
</body>
</html>