    {{-- 사업자 정보 등록 모달 --}}
    <div id="business-info-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="business-info-form">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                    사업자 정보 등록
                                </h3>

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">사업체명 *</label>
                                        <input type="text" name="business_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">사업자등록번호 *</label>
                                        <input type="text" name="business_registration_number" required placeholder="123-45-67890" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">대표자명 *</label>
                                        <input type="text" name="representative_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">업종</label>
                                            <input type="text" name="business_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">업태</label>
                                            <input type="text" name="business_item" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">주소 *</label>
                                        <input type="text" name="address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">상세주소</label>
                                        <input type="text" name="detail_address" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">연락처</label>
                                            <input type="text" name="phone" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">이메일</label>
                                            <input type="email" name="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            저장
                        </button>
                        <button type="button" id="cancel-business-info" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            취소
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 결제 수단 추가 모달 --}}
    <div id="paymentMethodModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">결제 수단 추가</h3>
                    <button id="closePaymentModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="paymentMethodForm">
                    <div class="space-y-4">
                        <div>
                            <label for="cardType" class="block text-sm font-medium text-gray-700 mb-2">카드 종류</label>
                            <select id="cardType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <option value="VISA">VISA</option>
                                <option value="MASTER">MasterCard</option>
                                <option value="SAMSUNG">삼성카드</option>
                                <option value="KB">KB국민카드</option>
                                <option value="HYUNDAI">현대카드</option>
                            </select>
                        </div>

                        <div>
                            <label for="cardNumber" class="block text-sm font-medium text-gray-700 mb-2">카드 번호</label>
                            <input type="text" id="cardNumber" placeholder="1234-5678-9012-3456" maxlength="19"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="expiryDate" class="block text-sm font-medium text-gray-700 mb-2">만료일</label>
                                <input type="text" id="expiryDate" placeholder="MM/YY" maxlength="5"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="cvv" class="block text-sm font-medium text-gray-700 mb-2">CVV</label>
                                <input type="text" id="cvv" placeholder="123" maxlength="3"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <div>
                            <label for="cardholderName" class="block text-sm font-medium text-gray-700 mb-2">카드 소유자명</label>
                            <input type="text" id="cardholderName" placeholder="카드에 표시된 이름"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="setAsDefault" class="rounded text-blue-600 focus:ring-blue-500">
                            <label for="setAsDefault" class="ml-2 text-sm text-gray-700">기본 결제 수단으로 설정</label>
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button type="submit" id="savePaymentMethod" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400">
                            저장
                        </button>
                        <button type="button" id="cancelPaymentMethod" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            취소
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- 요금제 변경 모달 --}}
    <div id="planChangeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">요금제 변경</h3>
                    <button id="closePlanModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- 동적으로 생성될 플랜 목록 컨테이너 --}}
                <div id="plans-container" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- 로딩 상태 --}}
                    <div id="plans-loading" class="col-span-full text-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">요금제 정보를 불러오는 중...</p>
                    </div>
                </div>

                <div id="planChangeConfirm" class="hidden">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                        <h4 class="font-medium text-blue-900 mb-2">선택한 플랜</h4>
                        <div class="flex items-center justify-between">
                            <div>
                                <span id="selectedPlanName" class="text-blue-900 font-medium"></span>
                                <span id="selectedPlanPrice" class="text-blue-700"></span>
                            </div>
                            <div class="text-sm text-blue-600">
                                다음 결제일: <span id="nextBillingDate"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button id="confirmPlanChange" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400">
                            플랜 변경하기
                        </button>
                        <button id="cancelPlanChange" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                            취소
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 결제 내역 상세 모달 --}}
    <div id="billingDetailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">결제 내역 상세</h3>
                    <button id="closeBillingDetailModal" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <div id="billingDetailContent">
                    {{-- 기본 정보 --}}
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">결제 일시</label>
                                <p id="detailDate" class="text-gray-900 font-medium">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">결제 상태</label>
                                <span id="detailStatus" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">-</span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">결제 금액</label>
                                <p id="detailAmount" class="text-gray-900 font-medium text-lg">-</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">결제 ID</label>
                                <p id="detailPaymentId" class="text-gray-600 text-sm font-mono">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- 플랜 정보 --}}
                    <div class="border border-gray-200 rounded-lg p-4 mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">플랜 정보</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">플랜명</span>
                                <span id="detailPlanName" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">사용 기간</span>
                                <span id="detailPeriod" class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">결제 방식</span>
                                <span id="detailBillingCycle" class="font-medium">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- 결제 수단 정보 --}}
                    <div class="border border-gray-200 rounded-lg p-4 mb-6">
                        <h4 class="font-medium text-gray-900 mb-3">결제 수단</h4>
                        <div class="flex items-center gap-3">
                            <div id="detailCardIcon" class="w-12 h-8 bg-gray-400 rounded flex items-center justify-center">
                                <span class="text-white text-xs font-bold">-</span>
                            </div>
                            <div>
                                <p id="detailCardNumber" class="font-medium">-</p>
                                <p id="detailCardInfo" class="text-sm text-gray-500">-</p>
                            </div>
                        </div>
                    </div>

                    {{-- 결제 상세 내역 --}}
                    <div class="border border-gray-200 rounded-lg p-4 mb-6" id="billingBreakdown">
                        <h4 class="font-medium text-gray-900 mb-3">결제 내역</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">-</span>
                                <span class="font-medium">-</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">VAT (10%)</span>
                                <span>-</span>
                            </div>
                            <hr class="border-gray-200">
                            <div class="flex justify-between font-medium text-lg">
                                <span>총 결제 금액</span>
                                <span id="detailTotalAmount">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- 실패 정보 (실패 시에만 표시) --}}
                    <div id="failureInfo" class="border border-red-200 bg-red-50 rounded-lg p-4 mb-6 hidden">
                        <h4 class="font-medium text-red-900 mb-3">결제 실패 정보</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-red-700">실패 사유</span>
                                <span id="failureReason" class="font-medium text-red-900">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">실패 시간</span>
                                <span id="failureTime" class="font-medium text-red-900">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-red-700">에러 코드</span>
                                <span id="errorCode" class="font-medium font-mono text-red-900">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- 환불 정보 (환불 시에만 표시) --}}
                    <div id="refundInfo" class="border border-purple-200 bg-purple-50 rounded-lg p-4 mb-6 hidden">
                        <h4 class="font-medium text-purple-900 mb-3">환불 정보</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-purple-700">환불 사유</span>
                                <span id="refundReason" class="font-medium text-purple-900">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700">환불 금액</span>
                                <span id="refundAmount" class="font-medium text-purple-900">-</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-700">환불 처리일</span>
                                <span id="refundDate" class="font-medium text-purple-900">-</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 액션 버튼 --}}
                <div class="flex gap-3 mt-6">
                    <button id="downloadReceiptBtn" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400">
                        영수증 다운로드
                    </button>
                    <button id="retryPaymentBtn" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 hidden">
                        결제 재시도
                    </button>
                    <button id="contactSupportBtn" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        고객지원
                    </button>
                </div>
            </div>
        </div>
    </div>
