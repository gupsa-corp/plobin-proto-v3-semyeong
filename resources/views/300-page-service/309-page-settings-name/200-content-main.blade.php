<div class="px-6 py-6">
    <!-- 페이지 설정 탭 네비게이션 -->
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

    <!-- 페이지 이름 변경 Livewire 컴포넌트 -->
    @livewire('page-settings-name', ['pageId' => request()->route('pageId')])
</div>