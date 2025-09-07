{{-- 플랫폼 관리자 권한 관리 메인 콘텐츠 --}}
<div class="p-6">
    {{-- 탭 네비게이션 --}}
    @include('900-page-platform-admin.905-page-permissions.300-tab-navigation')

    {{-- 역할 관리 탭 --}}
    @include('900-page-platform-admin.905-page-permissions.301-tab-roles')

    {{-- 권한 설정 탭 --}}
    @include('900-page-platform-admin.905-page-permissions.302-tab-permissions')

    {{-- 사용자 권한 탭 --}}
    @include('900-page-platform-admin.905-page-permissions.303-tab-users')

    {{-- 권한 로그 탭 --}}
    @include('900-page-platform-admin.905-page-permissions.304-tab-audit')
</div>

{{-- 새 역할 생성 모달 --}}
@include('900-page-platform-admin.905-page-permissions.310-modal-create-role')

{{-- JavaScript --}}
@include('900-page-platform-admin.905-page-permissions.400-js-permissions')