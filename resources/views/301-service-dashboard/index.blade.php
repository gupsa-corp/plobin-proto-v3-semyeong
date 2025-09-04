<!DOCTYPE html>
<html lang="ko">
@include('300-service-common.head', ['title' => '대시보드'])
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('300-service-common.sidebar')
        <div class="flex-1 flex flex-col">
            @include('300-service-common.header')
            @include('301-service-dashboard.body')
            @include('300-service-common.footer')
        </div>
    </div>
</body>
</html>
