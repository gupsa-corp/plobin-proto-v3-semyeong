{{-- 모달 관리 시스템 (분할된 파일들) --}}

{{-- 기존 공통 함수들 --}}
@include('300-page-service.300-common.500-ajax-post')

{{-- 1. 모달 UI 관리자 --}}
@include('300-page-service.306-page-organizations-list.403-js-2-modal-ui-manager')

{{-- 2. 조직 관리자 --}}
@include('300-page-service.306-page-organizations-list.403-js-3-organization-manager')

{{-- 3. 이벤트 핸들러 --}}
@include('300-page-service.306-page-organizations-list.403-js-4-event-handler')