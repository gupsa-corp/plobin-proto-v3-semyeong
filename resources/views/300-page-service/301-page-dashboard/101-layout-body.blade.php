<main class="service-main flex-1 p-6">

    {{-- 공통 JavaScript 컴포넌트들 --}}
    @include('000-common-javascript.api.error-handler')
    @include('000-common-javascript.ajax.api-client')
    @include('000-common-javascript.auth.authentication-manager')
    @include('000-common-javascript.view.modal-utils')
    @include('000-common-javascript.ui.dashboard-sidebar')
</main>
