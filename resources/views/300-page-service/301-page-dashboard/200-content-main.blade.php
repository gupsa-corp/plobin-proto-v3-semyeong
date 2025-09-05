{{-- 대시보드 메인 콘텐츠 --}}
<div class="dashboard-content" style="padding: 24px;" x-data="dashboardMain">
    {{-- 조직 선택 화면 --}}
    @include('300-page-service.301-page-dashboard.201-content-organization-selection')
    
    {{-- 대시보드 실제 콘텐츠는 여기에 추가 --}}
    
    {{-- 조직 생성 모달 --}}
    @include('300-page-service.301-page-dashboard.300-modal-create-organization')
    
    {{-- 조직 생성 완료 모달 --}}
    @include('300-page-service.301-page-dashboard.301-modal-create-success')
    
    {{-- 조직 관리 모달은 제거됨 (다른 모달들을 사용) --}}
</div>
