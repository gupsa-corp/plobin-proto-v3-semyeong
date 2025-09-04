<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Plobin' }} - 관리자</title>

    <!-- 관리자용 Vite 번들 -->
    @vite(['resources/css/900-admin-common.css', 'resources/js/900-admin-common.js'])
    @stack('styles')

    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js for admin dashboards -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind Config for Dark Theme -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        admin: {
                            bg: '#111827',
                            sidebar: '#1f2937',
                            header: '#1f2937',
                            card: '#374151',
                            border: '#4b5563',
                            text: '#f9fafb',
                            'text-secondary': '#d1d5db',
                            accent: '#3b82f6',
                            danger: '#ef4444',
                            success: '#10b981',
                            warning: '#f59e0b'
                        }
                    }
                }
            },
            darkMode: 'class'
        }
    </script>

    @stack('scripts')
</head>
