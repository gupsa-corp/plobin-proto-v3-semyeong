<!DOCTYPE html>
<html lang="ko">
@include('900-admin-common.head', ['title' => '관리자 대시보드'])
<body class="admin-body">
    <div class="flex min-h-screen">
        @include('900-admin-common.sidebar')
        <div class="flex-1 flex flex-col">
            @include('900-admin-common.header')
            @include('901-admin-dashboard.body')
            @include('900-admin-common.footer')
        </div>
    </div>
</body>
</html>
