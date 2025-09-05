<main class="service-main flex-1 p-6">
    {{-- 인증 확인 화면 --}}
    @include('301-service-dashboard.200-content-auth-check')

    {{-- 조직 선택 화면 --}}
    @include('301-service-dashboard.201-content-organization-selection')

    {{-- 조직 생성 모달 --}}
    @include('301-service-dashboard.300-modal-create-organization')

    {{-- 조직 생성 완료 모달 --}}
    @include('301-service-dashboard.301-modal-create-success')

    {{-- 조직 관리 모달 --}}
    @include('301-service-dashboard.302-modal-organization-manager')

    {{-- 공통 JavaScript 컴포넌트들 --}}
    @include('000-common-javascript.alpine-init')
    @include('000-common-javascript.api.error-handler')
    @include('000-common-javascript.ajax.api-client')
    @include('000-common-javascript.auth.authentication-manager')
    @include('000-common-javascript.view.modal-utils')
    @include('000-common-javascript.ui.dashboard-sidebar')
    
    {{-- 대시보드별 JavaScript 파일들 --}}
    @include('301-service-dashboard.400-js-dashboard')
    @include('301-service-dashboard.401-js-organization-selection')
    @include('301-service-dashboard.402-js-organization-modal')
</main>
