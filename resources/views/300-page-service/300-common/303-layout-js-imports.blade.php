{{-- JavaScript 임포트 --}}
<!-- 중앙집중식 인증 관리자 (우선 로드) -->
@include('300-page-service.300-common.005-auth-manager')

<!-- 중앙집중식 API 클라이언트 -->
@include('300-page-service.300-common.006-api-client')

<!-- Alpine.js 초기화 -->
@include('300-page-service.300-common.900-alpine-init')

<!-- 공통 JavaScript 모듈 -->
@include('000-common-javascript.100-index')

<!-- 대시보드 컴포넌트 등록 (head에서) -->
@if(request()->is('dashboard') || request()->is('dashboard/*'))
    @include('300-page-service.301-page-dashboard.400-js-dashboard')
@endif

@stack('scripts')