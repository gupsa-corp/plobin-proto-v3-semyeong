<?php $common = '800-page-organization-admin.800-common'; ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '플랜 계산기'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')

            <div class="p-6">
                <!-- 헤더 -->
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-bold text-gray-900">플랜 계산기</h1>
                        <a href="/organizations/{{ request()->route('organizationId') ?? request()->route('organization')->id ?? 1 }}/admin/billing" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            ← 빌링 관리로 돌아가기
                        </a>
                    </div>
                    <p class="text-gray-600 mt-2">라이센스 개수와 플랜을 선택하여 요금을 계산하세요</p>
                </div>

                <!-- 계산기 메인 컨텐츠 -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- 좌측: 플랜 선택 및 설정 -->
                    <div class="space-y-6">
                        <!-- 라이센스 개수 설정 -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">라이센스 설정</h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="licenseCount" class="block text-sm font-medium text-gray-700 mb-2">
                                        라이센스 개수
                                    </label>
                                    <div class="flex items-center space-x-4">
                                        <button type="button" id="decreaseLicense" 
                                                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                            <span class="text-lg font-bold">-</span>
                                        </button>
                                        <input type="number" id="licenseCount" 
                                               class="w-20 text-center border-gray-300 rounded-md" 
                                               value="1" min="1" max="10000">
                                        <button type="button" id="increaseLicense" 
                                                class="w-8 h-8 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center">
                                            <span class="text-lg font-bold">+</span>
                                        </button>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1">최소 1개, 최대 10,000개</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        결제 주기
                                    </label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="billingCycle" value="monthly" checked 
                                                   class="form-radio text-blue-600">
                                            <span class="ml-2">월간</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="billingCycle" value="yearly" 
                                                   class="form-radio text-blue-600">
                                            <span class="ml-2">연간 (10% 할인)</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 플랜 선택 -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">플랜 선택</h3>
                            <div class="space-y-3">
                                <div class="plan-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors" 
                                     data-plan="basic">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Basic</h4>
                                            <p class="text-sm text-gray-600">기본 기능</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900">₩10,000</span>
                                            <span class="text-sm text-gray-500">/월/라이센스</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="plan-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors" 
                                     data-plan="pro">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Pro</h4>
                                            <p class="text-sm text-gray-600">고급 기능 포함</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900">₩20,000</span>
                                            <span class="text-sm text-gray-500">/월/라이센스</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="plan-option p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-blue-300 transition-colors" 
                                     data-plan="enterprise">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <h4 class="font-medium text-gray-900">Enterprise</h4>
                                            <p class="text-sm text-gray-600">모든 기능 + 24/7 지원</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-lg font-bold text-gray-900">₩50,000</span>
                                            <span class="text-sm text-gray-500">/월/라이센스</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 우측: 계산 결과 -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">요금 계산</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">선택된 플랜:</span>
                                    <span id="selectedPlan" class="font-medium">Basic</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">라이센스 개수:</span>
                                    <span id="displayLicenseCount" class="font-medium">1</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">라이센스당 요금:</span>
                                    <span id="pricePerLicense" class="font-medium">₩10,000</span>
                                </div>
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">결제 주기:</span>
                                    <span id="displayBillingCycle" class="font-medium">월간</span>
                                </div>
                                
                                <hr class="border-gray-200">
                                
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">소계:</span>
                                    <span id="subtotal" class="font-medium">₩10,000</span>
                                </div>
                                
                                <div class="flex justify-between items-center text-green-600" id="discountRow" style="display: none;">
                                    <span>연간 할인 (10%):</span>
                                    <span id="discountAmount">-₩0</span>
                                </div>
                                
                                <hr class="border-gray-300">
                                
                                <div class="flex justify-between items-center text-xl font-bold">
                                    <span>총 금액:</span>
                                    <span id="totalAmount">₩10,000</span>
                                </div>
                                
                                <div class="text-sm text-gray-500" id="billingNote">
                                    매월 청구됩니다
                                </div>
                            </div>
                            
                            <div class="mt-6 space-y-3">
                                <button type="button" id="applyPlan" 
                                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors">
                                    이 플랜으로 변경
                                </button>
                                <button type="button" onclick="window.history.back()" 
                                        class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-3 px-4 rounded-lg transition-colors">
                                    취소
                                </button>
                            </div>
                        </div>

                        <!-- 플랜 비교 -->
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">플랜 기능 비교</h3>
                            <div class="space-y-3 text-sm">
                                <div class="grid grid-cols-4 gap-2 font-medium border-b pb-2">
                                    <span>기능</span>
                                    <span class="text-center">Basic</span>
                                    <span class="text-center">Pro</span>
                                    <span class="text-center">Enterprise</span>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <span>사용자 수</span>
                                    <span class="text-center">제한적</span>
                                    <span class="text-center">무제한</span>
                                    <span class="text-center">무제한</span>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <span>데이터 백업</span>
                                    <span class="text-center">주간</span>
                                    <span class="text-center">일간</span>
                                    <span class="text-center">실시간</span>
                                </div>
                                <div class="grid grid-cols-4 gap-2">
                                    <span>기술 지원</span>
                                    <span class="text-center">이메일</span>
                                    <span class="text-center">이메일+채팅</span>
                                    <span class="text-center">24/7 전화</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 토스페이먼츠 SDK -->
    <script src="https://js.tosspayments.com/v1/payment"></script>
    
    @include('800-page-organization-admin.803-page-billing.360-plan-calculator-scripts')
</body>
</html>