<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Plobin' }} - 서비스</title>

    <!-- 서비스용 스타일 -->
    @include('300-service-style-common.sidebar')
    @include('300-service-style-common.modal')
    @include('000-common-javascript.modal-styles')
    @stack('styles')

    <!-- 공통 JavaScript 모듈 -->
    @include('000-common-javascript.index')

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

    @stack('scripts')
</head>
