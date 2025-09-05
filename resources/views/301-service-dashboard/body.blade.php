<main class="service-main flex-1 p-6">
    {{-- 조직 선택 화면 --}}
    @include('301-service-dashboard.organization-selection')

    {{-- 메인 대시보드 화면 --}}
    @include('301-service-dashboard.main-dashboard')

    {{-- 조직 생성 요청 모달 --}}
    @include('301-service-dashboard-modal-001-create-organization.modal')

    {{-- 조직 생성 완료 모달 --}}
    @include('301-service-dashboard-modal-002-create-organization-success.modal')

    {{-- JavaScript 로직 포함 --}}
    @include('301-service-dashboard.javascript')
</main>
