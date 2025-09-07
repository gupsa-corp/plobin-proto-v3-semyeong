<?php $common = '800-page-organization-admin.800-common'; ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '결제 완료'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')

            <div class="p-6">
                <div class="max-w-2xl mx-auto">
                    <!-- 성공 메시지 -->
                    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                        <div class="w-16 h-16 mx-auto bg-green-100 rounded-full flex items-center justify-center mb-6">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        
                        <h1 class="text-2xl font-bold text-gray-900 mb-4">결제가 완료되었습니다!</h1>
                        <p class="text-gray-600 mb-8">플랜 변경이 성공적으로 처리되었습니다.</p>
                        
                        <!-- 결제 정보 -->
                        <div class="bg-gray-50 rounded-lg p-6 mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">결제 정보</h3>
                            <div class="space-y-3 text-left">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">결제 번호:</span>
                                    <span id="paymentKey" class="font-mono">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">주문 번호:</span>
                                    <span id="orderId" class="font-mono">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">플랜:</span>
                                    <span id="planName" class="font-semibold">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">라이센스 수:</span>
                                    <span id="licenseCount">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">결제 주기:</span>
                                    <span id="billingCycle">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">결제 금액:</span>
                                    <span id="totalAmount" class="text-xl font-bold text-green-600">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">결제 방법:</span>
                                    <span id="paymentMethod">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">결제 일시:</span>
                                    <span id="approvedAt">-</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 로딩 상태 -->
                        <div id="loading" class="mb-8">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                            <p class="text-gray-600">결제 정보를 확인하고 플랜을 업데이트하고 있습니다...</p>
                        </div>
                        
                        <!-- 성공 상태 -->
                        <div id="success" class="mb-8 hidden">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-green-800">플랜이 성공적으로 변경되었습니다!</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 에러 상태 -->
                        <div id="error" class="mb-8 hidden">
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-red-800">플랜 업데이트 중 문제가 발생했습니다. 고객지원에 문의해주세요.</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- 버튼들 -->
                        <div class="space-y-3">
                            <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing" 
                               class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                빌링 관리로 이동
                            </a>
                            <button id="downloadReceipt" 
                                    class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                영수증 다운로드
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('800-page-organization-admin.803-page-billing.380-payment-success-scripts')
</body>
</html>