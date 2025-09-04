<main class="service-main flex-1 p-6">
    {{-- 인증 체크 컴포넌트 --}}
    @include('501-service-block-auth-check.auth-check')

    {{-- 대시보드 메인 콘텐츠 --}}
    <div id="dashboardContent" class="hidden">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900">대시보드</h1>
        </div>
    </div>

    {{-- AJAX 로직 포함 --}}
    @include('301-service-dashboard.ajax')

    {{-- JavaScript 로직 포함 --}}
    @include('301-service-dashboard.javascript')
</main>
