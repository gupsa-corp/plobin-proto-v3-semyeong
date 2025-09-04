<!DOCTYPE html>
<html lang="ko">
@include('000-common-service.head', ['title' => '대시보드'])
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('000-common-service.sidebar')
        <div class="flex-1 flex flex-col">
            @include('000-common-service.header')
            @include('101-service-dashboard.body')
            @include('000-common-service.footer')
        </div>
    </div>
</body>
</html>