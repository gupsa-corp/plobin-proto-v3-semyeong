<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '권한 관리 - 역할'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')
            
            {{-- 역할 관리 Livewire 컴포넌트 --}}
            <livewire:organization.admin.permission-roles :organizationId="request()->route('id')" />
        </div>
    </div>
</body>
</html>