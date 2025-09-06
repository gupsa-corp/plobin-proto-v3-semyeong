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

<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .transition {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .ease-out {
        transition-timing-function: cubic-bezier(0, 0, 0.2, 1);
    }
    
    .ease-in {
        transition-timing-function: cubic-bezier(0.4, 0, 1, 1);
    }
    
    .duration-300 {
        transition-duration: 300ms;
    }
    
    .duration-200 {
        transition-duration: 200ms;
    }
</style>
