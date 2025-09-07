<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>파일 미리보기 - 샌드박스</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <h1 class="text-3xl font-bold py-6 text-center bg-white border-b">AI 샌드박스</h1>
        
        @include('700-page-sandbox.000-common-navigation')
        
        <div class="container mx-auto py-8">
            <h2 class="text-2xl font-bold mb-6">파일 미리보기 컴포넌트</h2>
        <div>
            @php
                $fileName = 'example.blade.php';
                $content = '<div class="p-4">예시 블레이드 템플릿 내용</div>';
            @endphp
                @include('700-page-sandbox.705-page-file-preview.200-content-main')
            </div>
        </div>
    </div>
    @livewireScripts
</body>
</html>