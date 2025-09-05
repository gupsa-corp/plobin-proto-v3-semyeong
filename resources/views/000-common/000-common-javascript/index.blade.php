{{-- 서비스 공통 JavaScript 모듈 전체 로더 --}}

{{-- API 관련 모듈 --}}
@include('000-common-javascript.api.error-handler')
@include('000-common-javascript.ajax.api-client')

{{-- 인증 관련 모듈 --}}
@include('000-common-javascript.auth.authentication-manager')

{{-- UI 관련 모듈 --}}
@include('000-common-javascript.view.modal-utils')
@include('000-common-javascript.ui.dashboard-sidebar')
