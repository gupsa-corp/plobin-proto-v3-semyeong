<!-- 페이지 설정 탭 네비게이션 -->
@include('300-page-service.309-page-settings-name.100-tab-navigation')

<!-- 페이지 삭제 Livewire 컴포넌트 -->
@livewire('page-settings-delete', ['pageId' => request()->route('pageId')])