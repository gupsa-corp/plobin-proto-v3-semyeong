{{-- 모달 관리 시스템 (분할된 파일들) --}}

{{-- 기존 공통 함수들 --}}
@include('300-page-service.300-common.000-auth-token-manager')
@include('300-page-service.300-common.500-ajax-post')

{{-- 1. API 클라이언트 --}}
@include('300-page-service.306-page-organizations-list.403-js-1-api-client')

{{-- 2. 모달 UI 관리자 --}}
@include('300-page-service.306-page-organizations-list.403-js-2-modal-ui-manager')

{{-- 3. 조직 관리자 --}}
@include('300-page-service.306-page-organizations-list.403-js-3-organization-manager')

{{-- 4. 이벤트 핸들러 --}}
@include('300-page-service.306-page-organizations-list.403-js-4-event-handler')