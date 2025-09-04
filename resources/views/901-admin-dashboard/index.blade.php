<!DOCTYPE html>
<html lang="ko">
@include('000-common-admin.head', ['title' => '관리자 대시보드'])
<body class="admin-body">
    <div class="flex min-h-screen">
        @include('000-common-admin.sidebar')
        <div class="flex-1 flex flex-col">
            @include('000-common-admin.header')
            @include('901-admin-dashboard.body')
            @include('000-common-admin.footer')
        </div>
    </div>
</body>
</html>