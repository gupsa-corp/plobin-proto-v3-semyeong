{{-- CSS 임포트 --}}
<!-- 서비스용 스타일 -->
@include('000-common-javascript.301-modal-styles')
@stack('styles')

<script src="https://cdn.tailwindcss.com"></script>

<!-- Tailwind Config -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50: '#f0f9ff',
                        500: '#3b82f6',
                        600: '#2563eb',
                        700: '#1d4ed8',
                    }
                }
            }
        }
    }
</script>
