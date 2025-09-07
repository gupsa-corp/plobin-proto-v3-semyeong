<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '결제 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')

            {{-- 결제 관리 메인 콘텐츠 --}}
            <div class="p-6">
                @include('800-page-organization-admin.803-page-billing.301-billing-header')
                @include('800-page-organization-admin.803-page-billing.302-billing-subscription-status')
                @include('800-page-organization-admin.803-page-billing.304-billing-payment-methods')
                @include('800-page-organization-admin.803-page-billing.305-billing-payment-history')
            </div>
        </div>
    </div>

    @include('800-page-organization-admin.803-page-billing.310-billing-modals')
    @include('800-page-organization-admin.803-page-billing.320-billing-scripts')
</body>
</html>
