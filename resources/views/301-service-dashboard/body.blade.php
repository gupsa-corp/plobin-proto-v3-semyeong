<main class="service-main flex-1 p-6">
    {{-- 인증 체크 컴포넌트 --}}
    @include('501-service-block-auth-check.auth-check')

    {{-- AJAX 로직 포함 --}}
    @include('301-service-dashboard.ajax')

    {{-- JavaScript 로직 포함 --}}
    @include('301-service-dashboard.javascript')
</main>
