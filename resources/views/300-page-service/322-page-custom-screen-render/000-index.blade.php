<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $screen['title'] }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* 커스텀 스타일을 위한 기본 설정 */
        .custom-screen-container {
            min-height: 100vh;
            background: #f8fafc;
        }
        
        /* 커스텀 화면에서 사용할 수 있는 기본 스타일들 */
        .dashboard {
            @apply bg-white rounded-lg shadow-sm p-6;
        }
        
        .dashboard h1, .dashboard h2, .dashboard h3 {
            @apply text-gray-900 font-semibold mb-4;
        }
        
        .dashboard h1 { @apply text-2xl; }
        .dashboard h2 { @apply text-xl; }
        .dashboard h3 { @apply text-lg; }
        
        .dashboard p {
            @apply text-gray-600 mb-3;
        }
        
        .product-list {
            @apply bg-white rounded-lg shadow-sm p-6;
        }
        
        .product-list h2 {
            @apply text-xl font-semibold text-gray-900 mb-4;
        }
        
        .products {
            @apply space-y-3;
        }
        
        .contact-form {
            @apply max-w-md mx-auto bg-white rounded-lg shadow-sm p-6;
        }
        
        .contact-form h2 {
            @apply text-xl font-semibold text-gray-900 mb-6 text-center;
        }
        
        .contact-form input,
        .contact-form textarea {
            @apply w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 mb-4;
        }
        
        .contact-form button {
            @apply w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition-colors font-medium;
        }
    </style>
</head>
<body class="custom-screen-container">
    <!-- 헤더 -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <h1 class="text-lg font-semibold text-gray-900">{{ $screen['title'] }}</h1>
                    <span class="ml-3 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                        {{ ucfirst($screen['type']) }}
                    </span>
                </div>
                
                <div class="text-sm text-gray-500">
                    조직 {{ $orgId }} · 프로젝트 {{ $projectId }} · 페이지 {{ $pageId }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- 메인 컨텐츠 -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <p class="text-gray-600">{{ $screen['description'] }}</p>
        </div>
        
        <!-- 커스텀 화면 컨텐츠 렌더링 -->
        <div class="custom-screen-content">
            {!! $screen['content'] !!}
        </div>
    </div>
    
    <!-- 푸터 -->
    <div class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-between items-center text-sm text-gray-500">
                <div>
                    샌드박스에서 생성된 커스텀 화면
                </div>
                <div>
                    생성일: {{ $screen['created_at'] }}
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // 기본적인 상호작용을 위한 JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // 폼 제출 이벤트 처리 (예시)
            const forms = document.querySelectorAll('.contact-form form');
            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert('폼이 제출되었습니다! (데모용)');
                });
            });
            
            // 클릭 이벤트 처리 (예시)
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                if (!button.type || button.type !== 'submit') {
                    button.addEventListener('click', function(e) {
                        if (!button.onclick) {
                            console.log('버튼이 클릭되었습니다:', button.textContent);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>