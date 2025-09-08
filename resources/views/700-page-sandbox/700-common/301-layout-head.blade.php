<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - Plobin' : 'Plobin' }}</title>

    <!-- Vite 자산 로드 (Tailwind CSS 포함) -->
    @vite(['resources/css/app.css', 'resources/js/alpine.js'])

    <!-- 샌드박스 페이지 전용 스타일 -->
    @include('700-page-sandbox.700-common.302-layout-css-imports')

    <!-- FileManager 관련 스타일 (필요시 추가) -->

    @filamentStyles

    <!-- Livewire Styles -->
    @livewireStyles
</head>
