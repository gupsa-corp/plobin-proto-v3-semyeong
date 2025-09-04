<!DOCTYPE html>
<html lang="ko">
@include('00-landing.head')
<body class="bg-gray-50 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('00-landing.header')

        <main class="flex-1">
            @include('00-landing.body')
        </main>

        @include('00-landing.footer')
    </div>
</body>
</html>
