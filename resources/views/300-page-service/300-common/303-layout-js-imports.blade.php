{{-- JavaScript 임포트 --}}
<!-- 중앙집중식 API 클라이언트 -->
@once
    @include('300-page-service.300-common.000-api-client')
@endonce

{{-- Alpine.js는 Livewire에서 제공되므로 별도 로드하지 않음 --}}

<!-- 공통 JavaScript 모듈 -->
@include('000-common-javascript.100-index')

<!-- 대시보드 컴포넌트 등록 (head에서) -->
@if(request()->is('dashboard') || request()->is('dashboard/*'))
    @include('300-page-service.301-page-dashboard.400-js-dashboard')
@endif

@stack('scripts')
