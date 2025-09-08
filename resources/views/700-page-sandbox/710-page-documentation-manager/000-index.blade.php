<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>개발자 문서 관리 - Plobin</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    @include('700-page-sandbox.700-common.400-sandbox-header')

    <div class="min-h-screen">
        {{-- 문서 관리 Livewire 컴포넌트 --}}
        @livewire('sandbox.documentation-manager')
    </div>

    <!-- Livewire Scripts (includes Alpine.js) -->
    @livewireScripts

    {{-- 추가 스타일링 --}}
    <style>
        .markdown-preview h1 {
            @apply text-2xl font-bold text-gray-900 mt-8 mb-4 first:mt-0;
        }
        
        .markdown-preview h2 {
            @apply text-xl font-bold text-gray-900 mt-6 mb-3;
        }
        
        .markdown-preview h3 {
            @apply text-lg font-semibold text-gray-800 mt-4 mb-2;
        }
        
        .markdown-preview p {
            @apply mb-4 text-gray-700 leading-relaxed;
        }
        
        .markdown-preview ul {
            @apply mb-4 space-y-1;
        }
        
        .markdown-preview li {
            @apply text-gray-700;
        }
        
        .markdown-preview pre {
            @apply bg-gray-100 p-4 rounded-lg overflow-x-auto mb-4 text-sm;
        }
        
        .markdown-preview code {
            @apply bg-gray-100 px-2 py-1 rounded text-sm font-mono text-gray-800;
        }
        
        .markdown-preview pre code {
            @apply bg-transparent p-0 rounded-none;
        }
        
        .markdown-preview strong {
            @apply font-semibold text-gray-900;
        }
        
        .markdown-preview em {
            @apply italic;
        }
        
        .markdown-preview a {
            @apply text-indigo-600 hover:text-indigo-800 underline;
        }
        
        .markdown-preview blockquote {
            @apply border-l-4 border-gray-300 pl-4 italic text-gray-600 my-4;
        }
        
        .markdown-preview table {
            @apply w-full border-collapse mb-4;
        }
        
        .markdown-preview th,
        .markdown-preview td {
            @apply border border-gray-300 px-3 py-2 text-left;
        }
        
        .markdown-preview th {
            @apply bg-gray-100 font-semibold;
        }
        
        .markdown-preview hr {
            @apply my-6 border-gray-300;
        }
    </style>
</body>
</html>