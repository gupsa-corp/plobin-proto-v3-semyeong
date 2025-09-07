<?php $common = '800-page-organization-admin.800-common'; ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '결제 실패'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')

            <div class="p-6">
                <div class="max-w-2xl mx-auto">
                    <!-- 실패 메시지 -->
                    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                        <div class="w-16 h-16 mx-auto bg-red-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-4">결제가 실패되었습니다</h1>
                        <p class="text-gray-600 mb-8">결제 처리 중 문제가 발생했습니다.</p>
                        
                        <!-- 실패 정보 -->
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-red-800 mb-4">실패 상세 정보</h3>
                            <div class="space-y-3 text-left">
                                <div class="flex justify-between">
                                    <span class="text-red-600">실패 코드:</span>
                                    <span id="failCode" class="font-mono text-red-800">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-red-600">실패 메시지:</span>
                                    <span id="failMessage" class="text-red-800">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-red-600">주문 번호:</span>
                                    <span id="orderId" class="font-mono text-red-800">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 안내 메시지 -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                            <h4 class="font-semibold text-blue-800 mb-2">다시 시도해보세요</h4>
                            <ul class="text-sm text-blue-700 text-left space-y-1">
                                <li>• 카드 정보를 다시 확인해주세요</li>
                                <li>• 잔액이 충분한지 확인해주세요</li>
                                <li>• 다른 결제 방법을 시도해보세요</li>
                                <li>• 문제가 지속되면 고객지원에 문의해주세요</li>
                            </ul>
                        </div>
                        
                        <!-- 버튼들 -->
                        <div class="space-y-3">
                            <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing/plan-calculator" 
                               class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                다시 결제하기
                            </a>
                            <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing" 
                               class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                빌링 관리로 이동
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // URL 파라미터에서 실패 정보 추출
            const urlParams = new URLSearchParams(window.location.search);
            const failCode = urlParams.get('code');
            const failMessage = urlParams.get('message');
            const orderId = urlParams.get('orderId');

            // 실패 정보 표시
            if (failCode) {
                document.getElementById('failCode').textContent = failCode;
            }
            if (failMessage) {
                document.getElementById('failMessage').textContent = decodeURIComponent(failMessage);
            }
            if (orderId) {
                document.getElementById('orderId').textContent = orderId;
            }
        });
    </script>
</body>
</html>