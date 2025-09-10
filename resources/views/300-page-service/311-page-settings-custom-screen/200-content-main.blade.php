@include('300-page-service.311-page-settings-custom-screen.001-scripts-initialization')

<!-- 페이지 설정 탭 네비게이션 -->
<div class="px-6 py-6" x-data="customScreenSettingsPage()">
    @include('300-page-service.309-page-settings-name.100-tab-navigation')

    @include('300-page-service.311-page-settings-custom-screen.002-alert-messages')

    <!-- 커스텀 화면 선택 콘텐츠 -->
    <div class="bg-white rounded-lg border border-gray-200">
        @include('300-page-service.311-page-settings-custom-screen.003-page-header')

        <!-- 콘텐츠 -->
        <div class="p-6">
            @include('300-page-service.311-page-settings-custom-screen.004-sandbox-not-selected')
            @include('300-page-service.311-page-settings-custom-screen.005-custom-screen-form')
        </div>
    </div>
</div>

@include('300-page-service.311-page-settings-custom-screen.007-alpine-js-script')
