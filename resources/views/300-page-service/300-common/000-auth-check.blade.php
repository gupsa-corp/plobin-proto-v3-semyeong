{{-- 인증 체크 컴포넌트 --}}
@if(config('dashboard.auth_check_enabled', true))
    @guest
        <script>
            // 인증되지 않은 사용자는 로그인 페이지로 리디렉션
            window.location.href = '/login';
        </script>
    @endguest
@endif