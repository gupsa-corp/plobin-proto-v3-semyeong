<?php $common = getCommonPath(); ?>
<!DOCTYPE html>
@include('000-common-layouts.001-html-lang')
@include($common . '.301-layout-head', ['title' => '결제 관리'])
<body class="bg-gray-100">
    <div class="min-h-screen" style="position: relative;">
        @include('800-page-organization-admin.800-common.200-sidebar-main')
        <div class="main-content" style="margin-left: 240px; min-height: 100vh;">
            @include('800-page-organization-admin.800-common.100-header-main')

            {{-- 결제 관리 메인 콘텐츠 --}}
            <div class="p-6">
                {{-- 페이지 헤더 --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">결제 관리</h2>
                            <p class="text-gray-600 mt-1">조직의 요금제 및 결제 정보를 관리합니다</p>
                        </div>
                        <div class="flex gap-3">
                            <button id="register-business-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 hidden">
                                사업자 정보 등록
                            </button>
                            <button id="change-plan-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                                요금제 변경
                            </button>
                        </div>
                    </div>
                </div>

                {{-- 현재 플랜 정보 --}}
                <div id="subscription-card" class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg p-6 text-white mb-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h3 id="plan-name" class="text-xl font-semibold"></h3>
                                <span id="plan-status" class="px-2.5 py-0.5 bg-blue-500 rounded-full text-xs font-medium"></span>
                            </div>
                            <p class="text-blue-100 mb-4">팀 협업을 위한 플랜</p>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <p class="text-blue-100 text-sm">월 요금</p>
                                    <p id="monthly-price" class="text-2xl font-bold"></p>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-sm">다음 결제일</p>
                                    <p id="next-billing-date" class="text-lg font-medium"></p>
                                </div>
                                <div>
                                    <p class="text-blue-100 text-sm">사용 중인 멤버</p>
                                    <p id="current-members" class="text-lg font-medium"></p>
                                </div>
                            </div>
                        </div>
                        <div class="hidden md:block">
                            <div class="w-24 h-24 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 구독 없음 상태 --}}
                <div id="no-subscription-card" class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-lg p-8 text-center mb-6 hidden">
                    <div class="w-16 h-16 mx-auto bg-gray-200 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">활성 구독이 없습니다</h3>
                    <p class="text-gray-600 mb-4">플랜을 선택하여 서비스를 시작하세요</p>
                    <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                        플랜 선택하기
                    </button>
                </div>

                {{-- 사용량 및 한도 --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">월간 사용량</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">활성 멤버</span>
                                    <span class="text-sm font-medium">24 / 50</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 48%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">프로젝트</span>
                                    <span class="text-sm font-medium">12 / 무제한</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-green-600 h-2 rounded-full" style="width: 20%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">스토리지</span>
                                    <span class="text-sm font-medium">145GB / 500GB</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-yellow-500 h-2 rounded-full" style="width: 29%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">이번 달 요약</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">활성 사용자</p>
                                        <p class="font-medium">24명</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">₩99,000</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">추가 스토리지</p>
                                        <p class="font-medium">0GB</p>
                                    </div>
                                </div>
                                <span class="text-sm text-gray-500">₩0</span>
                            </div>

                            <div class="border-t pt-3">
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900">총 금액</span>
                                    <span class="font-bold text-gray-900">₩99,000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 결제 수단 --}}
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">결제 수단</h3>
                        <button id="addPaymentMethodBtn" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                            + 결제 수단 추가
                        </button>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">VISA</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">**** **** **** 1234</p>
                                    <p class="text-sm text-gray-500">만료일: 12/26</p>
                                </div>
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-800 text-xs font-medium rounded-full">기본</span>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="editPaymentMethod('default')" class="text-blue-600 hover:text-blue-800 text-sm">편집</button>
                                <button onclick="deletePaymentMethod('default')" class="text-red-600 hover:text-red-800 text-sm">삭제</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 결제 내역 --}}
                <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">결제 내역</h3>
                            <div class="flex gap-2">
                                <select class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                                    <option>최근 6개월</option>
                                    <option>최근 1년</option>
                                    <option>전체</option>
                                </select>
                                <button id="exportBillingHistoryBtn" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                                    내보내기
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">날짜</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">설명</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">금액</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">영수증</th>
                                </tr>
                            </thead>
                            <tbody id="billing-history-tbody" class="bg-white divide-y divide-gray-200">
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.03.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 3월 15일 - 4월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="downloadSpecificReceipt(1)" class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.02.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 2월 15일 - 3월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="downloadSpecificReceipt(1)" class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">2024.01.15</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">Pro 플랜 월간 구독</div>
                                            <div class="text-sm text-gray-500">2024년 1월 15일 - 2월 15일</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₩99,000</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">결제 완료</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button onclick="downloadSpecificReceipt(1)" class="text-blue-600 hover:text-blue-900">다운로드</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- 페이지네이션 --}}
                    <div id="paginationContainer" class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
                        <div id="paginationInfo" class="text-sm text-gray-500">
                            총 25건 중 1-10건 표시
                        </div>
                        <div id="paginationButtons" class="flex gap-1">
                            <button id="prevPageBtn" onclick="changePage('prev')" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500">이전</button>
                            <button onclick="changePage(1)" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm page-btn" data-page="1">1</button>
                            <button onclick="changePage(2)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 page-btn" data-page="2">2</button>
                            <button onclick="changePage(3)" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 page-btn" data-page="3">3</button>
                            <button id="nextPageBtn" onclick="changePage('next')" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700">다음</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Starter 플랜 --}}
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer plan-option" data-plan="starter">
                        <div class="text-center">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Starter</h4>
                            <div class="text-3xl font-bold text-gray-900 mb-1">₩29,000</div>
                            <div class="text-sm text-gray-500 mb-4">월간</div>
                            <ul class="text-left space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    최대 5명 멤버
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    10GB 스토리지
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    기본 지원
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Pro 플랜 --}}
                    <div class="border-2 border-blue-500 rounded-lg p-6 relative cursor-pointer plan-option" data-plan="pro">
                        <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-medium">추천</span>
                        </div>
                        <div class="text-center">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Pro</h4>
                            <div class="text-3xl font-bold text-gray-900 mb-1">₹99,000</div>
                            <div class="text-sm text-gray-500 mb-4">월간</div>
                            <ul class="text-left space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    최대 50명 멤버
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    500GB 스토리지
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    우선 지원
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    고급 분석
                                </li>
                            </ul>
                        </div>
                    </div>

                    {{-- Enterprise 플랜 --}}
                    <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-blue-500 transition-colors cursor-pointer plan-option" data-plan="enterprise">
                        <div class="text-center">
                            <h4 class="text-lg font-semibold text-gray-900 mb-2">Enterprise</h4>
                            <div class="text-3xl font-bold text-gray-900 mb-1">₩199,000</div>
                            <div class="text-sm text-gray-500 mb-4">월간</div>
                            <ul class="text-left space-y-2 text-sm text-gray-600">
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    무제한 멤버
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    무제한 스토리지
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    24/7 전용 지원
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    커스텀 통합
                                </li>
                            </ul>
                        </div>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 전역 변수
            let currentBillingData = null;
            let currentOrganizationId = {{ $id ?? 'null' }};

            // DOM 요소들 (안전하게 가져오기)
            const subscriptionCard = document.getElementById('subscription-card');
            const noSubscriptionCard = document.getElementById('no-subscription-card');
            const downloadReceiptBtn = document.getElementById('download-receipt-btn');
            const registerBusinessBtn = document.getElementById('register-business-btn');
            const businessInfoModal = document.getElementById('business-info-modal');
            const businessInfoForm = document.getElementById('business-info-form');
            const addPaymentMethodBtn = document.getElementById('addPaymentMethodBtn');
            const paymentMethodModal = document.getElementById('paymentMethodModal');
            const paymentMethodForm = document.getElementById('paymentMethodForm');
            const closePaymentModal = document.getElementById('closePaymentModal');
            const cancelPaymentMethod = document.getElementById('cancelPaymentMethod');
            const changePlanBtn = document.getElementById('change-plan-btn');
            const planChangeModal = document.getElementById('planChangeModal');
            const closePlanModal = document.getElementById('closePlanModal');
            const planOptions = document.querySelectorAll('.plan-option');
            const planChangeConfirm = document.getElementById('planChangeConfirm');
            const confirmPlanChange = document.getElementById('confirmPlanChange');
            const cancelPlanChange = document.getElementById('cancelPlanChange');
            const exportBillingHistoryBtn = document.getElementById('exportBillingHistoryBtn');

            // 페이지 로드 시 결제 데이터 가져오기
            loadBillingData();

            // 이벤트 리스너 (null 체크 추가)
            if (registerBusinessBtn) registerBusinessBtn.addEventListener('click', showBusinessInfoModal);
            const cancelBusinessInfoBtn = document.getElementById('cancel-business-info');
            if (cancelBusinessInfoBtn) cancelBusinessInfoBtn.addEventListener('click', hideBusinessInfoModal);
            if (businessInfoForm) businessInfoForm.addEventListener('submit', submitBusinessInfo);
            if (downloadReceiptBtn) downloadReceiptBtn.addEventListener('click', downloadReceipt);
            if (addPaymentMethodBtn) addPaymentMethodBtn.addEventListener('click', showPaymentMethodModal);
            if (closePaymentModal) closePaymentModal.addEventListener('click', hidePaymentMethodModal);
            if (cancelPaymentMethod) cancelPaymentMethod.addEventListener('click', hidePaymentMethodModal);
            if (paymentMethodForm) paymentMethodForm.addEventListener('submit', submitPaymentMethod);
            if (changePlanBtn) changePlanBtn.addEventListener('click', showPlanChangeModal);
            if (closePlanModal) closePlanModal.addEventListener('click', hidePlanChangeModal);
            if (cancelPlanChange) cancelPlanChange.addEventListener('click', hidePlanChangeModal);
            if (confirmPlanChange) confirmPlanChange.addEventListener('click', submitPlanChange);
            
            // 플랜 선택 이벤트 리스너
            planOptions.forEach(option => {
                option.addEventListener('click', () => selectPlan(option.dataset.plan));
            });
            
            if (exportBillingHistoryBtn) exportBillingHistoryBtn.addEventListener('click', exportBillingHistory);

            // 모달 백드롭 클릭 시 닫기 (null 체크 추가)
            if (businessInfoModal) {
                businessInfoModal.addEventListener('click', function(e) {
                    if (e.target === businessInfoModal) {
                        hideBusinessInfoModal();
                    }
                });
            }

            if (paymentMethodModal) {
                paymentMethodModal.addEventListener('click', function(e) {
                    if (e.target === paymentMethodModal) {
                        hidePaymentMethodModal();
                    }
                });
            }
            
            if (planChangeModal) {
                planChangeModal.addEventListener('click', function(e) {
                    if (e.target === planChangeModal) {
                        hidePlanChangeModal();
                    }
                });
            }

            /**
             * 결제 데이터 로드
             */
            async function loadBillingData() {
                if (!currentOrganizationId) return;

                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/data`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    currentBillingData = data;

                    updateUI(data);

                } catch (error) {
                    console.error('Failed to load billing data:', error);
                    showError('결제 정보를 불러오는 중 오류가 발생했습니다.');
                }
            }

            /**
             * UI 업데이트
             */
            function updateUI(data) {
                if (data.subscription) {
                    // 구독이 있는 경우
                    if (subscriptionCard) subscriptionCard.classList.remove('hidden');
                    if (noSubscriptionCard) noSubscriptionCard.classList.add('hidden');

                    const planNameEl = document.getElementById('plan-name');
                    const planStatusEl = document.getElementById('plan-status');
                    const monthlyPriceEl = document.getElementById('monthly-price');
                    const nextBillingDateEl = document.getElementById('next-billing-date');
                    const currentMembersEl = document.getElementById('current-members');

                    if (planNameEl) planNameEl.textContent = data.subscription.plan_name + ' 플랜';
                    if (planStatusEl) planStatusEl.textContent = data.subscription.is_active ? '활성' : '비활성';
                    if (monthlyPriceEl) monthlyPriceEl.textContent = '₩' + data.subscription.monthly_price.toLocaleString();
                    if (nextBillingDateEl) nextBillingDateEl.textContent = data.subscription.next_billing_date;
                    if (currentMembersEl) currentMembersEl.textContent = `${data.usage.members} / ${data.subscription.max_members || '무제한'}`;

                    // 사용량 업데이트
                    updateUsage(data.usage, data.subscription);
                } else {
                    // 구독이 없는 경우
                    if (subscriptionCard) subscriptionCard.classList.add('hidden');
                    if (noSubscriptionCard) noSubscriptionCard.classList.remove('hidden');
                }

                // 사업자 정보 상태에 따른 버튼 표시
                if (data.business_info && data.business_info.has_complete_info) {
                    if (downloadReceiptBtn) downloadReceiptBtn.disabled = false;
                    if (registerBusinessBtn) registerBusinessBtn.classList.add('hidden');
                } else {
                    if (downloadReceiptBtn) downloadReceiptBtn.disabled = true;
                    if (registerBusinessBtn) registerBusinessBtn.classList.remove('hidden');
                }

                // 결제 내역 업데이트
                updateBillingHistory(data.billing_histories);

                // 결제 수단 업데이트
                updatePaymentMethods(data.payment_methods);
            }

            /**
             * 사용량 업데이트
             */
            function updateUsage(usage, subscription) {
                // 기존 하드코딩된 사용량을 실제 데이터로 업데이트
                const usageElements = {
                    members: document.querySelector('[data-usage="members"]'),
                    projects: document.querySelector('[data-usage="projects"]'),
                    storage: document.querySelector('[data-usage="storage"]')
                };

                Object.keys(usageElements).forEach(type => {
                    const element = usageElements[type];
                    if (element) {
                        const current = usage[type];
                        const max = subscription['max_' + type] || '무제한';
                        const percentage = usage.usage_percentages ? usage.usage_percentages[type] : 0;

                        element.querySelector('.usage-text').textContent = `${current} / ${max}`;
                        element.querySelector('.usage-bar').style.width = percentage + '%';
                    }
                });
            }

            /**
             * 결제 내역 업데이트
             */
            function updateBillingHistory(histories) {
                const tbody = document.querySelector('#billing-history-tbody');
                if (!tbody) return;

                tbody.innerHTML = '';

                histories.forEach(history => {
                    const row = createBillingHistoryRow(history);
                    tbody.appendChild(row);
                });
            }

            /**
             * 결제 내역 행 생성
             */
            function createBillingHistoryRow(history) {
                const row = document.createElement('tr');
                row.className = 'hover:bg-gray-50';

                const statusColor = getStatusColor(history.status_color);

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_date}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${history.description}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${history.formatted_amount}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-${statusColor}-100 text-${statusColor}-800">
                            ${history.status_text}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        ${history.is_paid ? `<button onclick="downloadSpecificReceipt(${history.id})" class="text-blue-600 hover:text-blue-900">다운로드</button>` : '-'}
                    </td>
                `;

                return row;
            }

            /**
             * 상태 색상 매핑
             */
            function getStatusColor(color) {
                const colorMap = {
                    'green': 'green',
                    'yellow': 'yellow',
                    'red': 'red',
                    'gray': 'gray'
                };
                return colorMap[color] || 'gray';
            }

            /**
             * 사업자 정보 모달 표시
             */
            function showBusinessInfoModal() {
                // 기존 사업자 정보가 있으면 폼에 채워넣기
                if (currentBillingData && currentBillingData.business_info) {
                    const businessInfo = currentBillingData.business_info;
                    const form = businessInfoForm;

                    form.business_name.value = businessInfo.business_name || '';
                    form.business_registration_number.value = businessInfo.business_registration_number || '';
                    form.representative_name.value = businessInfo.representative_name || '';
                    form.business_type.value = businessInfo.business_type || '';
                    form.business_item.value = businessInfo.business_item || '';
                    form.address.value = businessInfo.address || '';
                    form.detail_address.value = businessInfo.detail_address || '';
                    form.phone.value = businessInfo.phone || '';
                    form.email.value = businessInfo.email || '';
                }

                businessInfoModal.classList.remove('hidden');
            }

            /**
             * 사업자 정보 모달 숨김
             */
            function hideBusinessInfoModal() {
                businessInfoModal.classList.add('hidden');
                businessInfoForm.reset();
            }

            /**
             * 사업자 정보 제출
             */
            async function submitBusinessInfo(e) {
                e.preventDefault();

                const formData = new FormData(businessInfoForm);
                const data = {};

                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }

                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/business-info`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data),
                        credentials: 'same-origin'
                    });

                    const result = await response.json();

                    if (result.success) {
                        hideBusinessInfoModal();
                        showSuccess(result.message);
                        loadBillingData(); // 데이터 새로고침
                    } else {
                        showError(result.message);
                    }

                } catch (error) {
                    console.error('Failed to save business info:', error);
                    showError('사업자 정보 저장 중 오류가 발생했습니다.');
                }
            }

            /**
             * 영수증 다운로드
             */
            async function downloadReceipt() {
                if (!currentBillingData || !currentBillingData.billing_histories.length) {
                    showError('다운로드할 영수증이 없습니다.');
                    return;
                }

                const latestPaidHistory = currentBillingData.billing_histories.find(h => h.is_paid);
                if (!latestPaidHistory) {
                    showError('완료된 결제 내역이 없습니다.');
                    return;
                }

                await downloadSpecificReceipt(latestPaidHistory.id);
            }

            /**
             * 특정 영수증 다운로드
             */
            window.downloadSpecificReceipt = async function(billingHistoryId) {
                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/receipt/download`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            billing_history_id: billingHistoryId
                        }),
                        credentials: 'same-origin'
                    });

                    if (response.headers.get('content-type').includes('application/pdf')) {
                        // PDF 다운로드
                        const blob = await response.blob();
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = `receipt_${billingHistoryId}.pdf`;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);
                    } else {
                        // JSON 응답 (URL 리다이렉션)
                        const result = await response.json();
                        if (result.success && result.receipt_url) {
                            window.open(result.receipt_url, '_blank');
                        } else {
                            showError(result.message || '영수증 다운로드에 실패했습니다.');
                        }
                    }

                } catch (error) {
                    console.error('Failed to download receipt:', error);
                    showError('영수증 다운로드 중 오류가 발생했습니다.');
                }
            };

            /**
             * 결제 수단 업데이트
             */
            function updatePaymentMethods(paymentMethods) {
                try {
                    const paymentMethodsContainer = document.querySelector('[data-payment-methods]');
                    if (!paymentMethodsContainer) {
                        console.log('결제 수단 컨테이너를 찾을 수 없습니다.');
                        return;
                    }

                    // 결제 수단 데이터가 있는 경우
                    if (paymentMethods && Array.isArray(paymentMethods)) {
                        // 기존 결제 수단 정보 업데이트
                        paymentMethods.forEach((method, index) => {
                            const methodElement = paymentMethodsContainer.querySelector(`[data-payment-method="${index}"]`);
                            if (methodElement) {
                                // 카드 정보 업데이트
                                const cardTypeElement = methodElement.querySelector('[data-card-type]');
                                const cardNumberElement = methodElement.querySelector('[data-card-number]');
                                const expiryElement = methodElement.querySelector('[data-card-expiry]');

                                if (cardTypeElement) cardTypeElement.textContent = method.type || 'CARD';
                                if (cardNumberElement) cardNumberElement.textContent = method.masked_number || '**** **** **** ****';
                                if (expiryElement) expiryElement.textContent = `만료일: ${method.expiry_date || 'MM/YY'}`;
                            }
                        });
                    }

                    console.log('결제 수단 정보 업데이트 완료:', paymentMethods);
                } catch (error) {
                    console.error('결제 수단 업데이트 중 오류:', error);
                }
            }

            /**
             * 결제 수단 추가 모달 표시
             */
            function showPaymentMethodModal() {
                paymentMethodModal.classList.remove('hidden');
                // 폼 초기화
                paymentMethodForm.reset();
                
                // 입력 필드 포맷팅 이벤트 리스너 추가
                setupPaymentFormFormatting();
            }
            
            /**
             * 결제 폼 입력 포맷팅 설정
             */
            function setupPaymentFormFormatting() {
                const cardNumberInput = document.getElementById('cardNumber');
                const expiryDateInput = document.getElementById('expiryDate');
                
                // 카드 번호 포맷팅 (XXXX-XXXX-XXXX-XXXX)
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    let formattedValue = value.replace(/(\d{4})(?=\d)/g, '$1-');
                    if (formattedValue.length > 19) {
                        formattedValue = formattedValue.substring(0, 19);
                    }
                    e.target.value = formattedValue;
                });
                
                // 만료일 포맷팅 (MM/YY)
                expiryDateInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }
            
            /**
             * 결제 수단 추가 모달 숨기기
             */
            function hidePaymentMethodModal() {
                paymentMethodModal.classList.add('hidden');
            }
            
            /**
             * 결제 수단 추가 폼 제출
             */
            async function submitPaymentMethod(e) {
                e.preventDefault();
                
                const cardType = document.getElementById('cardType').value;
                const cardNumber = document.getElementById('cardNumber').value;
                const expiryDate = document.getElementById('expiryDate').value;
                const cvv = document.getElementById('cvv').value;
                const cardholderName = document.getElementById('cardholderName').value;
                const setAsDefault = document.getElementById('setAsDefault').checked;
                
                // 유효성 검사
                if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
                    showError('모든 필드를 입력해주세요.');
                    return;
                }
                
                // 카드 번호 포맷 검사 (16자리 숫자)
                const cleanCardNumber = cardNumber.replace(/\D/g, '');
                if (cleanCardNumber.length !== 16) {
                    showError('올바른 카드 번호를 입력해주세요. (16자리)');
                    return;
                }
                
                // 만료일 포맷 검사 (MM/YY)
                const expiryPattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
                if (!expiryPattern.test(expiryDate)) {
                    showError('올바른 만료일을 입력해주세요. (MM/YY 형식)');
                    return;
                }
                
                // CVV 검사 (3자리 숫자)
                if (!/^\d{3}$/.test(cvv)) {
                    showError('올바른 CVV를 입력해주세요. (3자리 숫자)');
                    return;
                }
                
                try {
                    const saveButton = document.getElementById('savePaymentMethod');
                    saveButton.disabled = true;
                    saveButton.textContent = '저장 중...';
                    
                    // API 호출 시뮬레이션 (실제로는 백엔드 API 호출)
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/payment-methods`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            card_type: cardType,
                            card_number: cleanCardNumber,
                            expiry_date: expiryDate,
                            cvv: cvv,
                            cardholder_name: cardholderName,
                            is_default: setAsDefault
                        })
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        showSuccess('결제 수단이 성공적으로 추가되었습니다.');
                        hidePaymentMethodModal();
                        // 결제 데이터 새로고침
                        loadBillingData();
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '결제 수단 추가에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('결제 수단 추가 오류:', error);
                    showError(error.message || '결제 수단 추가 중 오류가 발생했습니다.');
                } finally {
                    const saveButton = document.getElementById('savePaymentMethod');
                    saveButton.disabled = false;
                    saveButton.textContent = '저장';
                }
            }

            /**
             * 플랜 변경 모달 표시
             */
            function showPlanChangeModal() {
                planChangeModal.classList.remove('hidden');
                planChangeConfirm.classList.add('hidden');
                // 모든 플랜 선택 초기화
                planOptions.forEach(option => {
                    option.classList.remove('border-blue-500', 'bg-blue-50');
                    option.classList.add('border-gray-200');
                });
            }
            
            /**
             * 플랜 변경 모달 숨기기
             */
            function hidePlanChangeModal() {
                planChangeModal.classList.add('hidden');
            }
            
            /**
             * 플랜 선택
             */
            function selectPlan(planType) {
                const planData = {
                    starter: { name: 'Starter', price: '₩29,000', monthly: 29000 },
                    pro: { name: 'Pro', price: '₩99,000', monthly: 99000 },
                    enterprise: { name: 'Enterprise', price: '₩199,000', monthly: 199000 }
                };
                
                const selectedPlan = planData[planType];
                if (!selectedPlan) return;
                
                // 플랜 선택 UI 업데이트
                planOptions.forEach(option => {
                    option.classList.remove('border-blue-500', 'bg-blue-50');
                    option.classList.add('border-gray-200');
                });
                
                const selectedOption = document.querySelector(`[data-plan="${planType}"]`);
                if (selectedOption) {
                    selectedOption.classList.remove('border-gray-200');
                    selectedOption.classList.add('border-blue-500', 'bg-blue-50');
                }
                
                // 확인 섹션 표시
                document.getElementById('selectedPlanName').textContent = selectedPlan.name + ' 플랜';
                document.getElementById('selectedPlanPrice').textContent = ` - ${selectedPlan.price}/월`;
                
                // 다음 결제일 계산 (현재 날짜 + 1개월)
                const nextMonth = new Date();
                nextMonth.setMonth(nextMonth.getMonth() + 1);
                document.getElementById('nextBillingDate').textContent = nextMonth.toLocaleDateString('ko-KR');
                
                planChangeConfirm.classList.remove('hidden');
                
                // 확인 버튼에 플랜 정보 저장
                confirmPlanChange.dataset.planType = planType;
                confirmPlanChange.dataset.planPrice = selectedPlan.monthly;
            }
            
            /**
             * 플랜 변경 확정
             */
            async function submitPlanChange() {
                const planType = confirmPlanChange.dataset.planType;
                const planPrice = confirmPlanChange.dataset.planPrice;
                
                if (!planType) {
                    showError('플랜을 선택해주세요.');
                    return;
                }
                
                try {
                    confirmPlanChange.disabled = true;
                    confirmPlanChange.textContent = '변경 중...';
                    
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/plan-change`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            plan_type: planType,
                            monthly_price: planPrice
                        })
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        showSuccess('플랜이 성공적으로 변경되었습니다.');
                        hidePlanChangeModal();
                        // 결제 데이터 새로고침
                        loadBillingData();
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '플랜 변경에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('플랜 변경 오류:', error);
                    showError(error.message || '플랜 변경 중 오류가 발생했습니다.');
                } finally {
                    confirmPlanChange.disabled = false;
                    confirmPlanChange.textContent = '플랜 변경하기';
                }
            }

            /**
             * 결제 내역 내보내기
             */
            async function exportBillingHistory() {
                const periodSelect = document.querySelector('select');
                const selectedPeriod = periodSelect ? periodSelect.value : '최근 6개월';
                
                try {
                    exportBillingHistoryBtn.disabled = true;
                    exportBillingHistoryBtn.textContent = '내보내는 중...';
                    
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/billing/export`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            period: selectedPeriod,
                            format: 'csv'
                        })
                    });
                    
                    if (response.ok) {
                        const contentType = response.headers.get('content-type');
                        
                        if (contentType && contentType.includes('text/csv')) {
                            // CSV 파일 다운로드
                            const blob = await response.blob();
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.style.display = 'none';
                            a.href = url;
                            a.download = `billing-history-${selectedPeriod.replace(/\s/g, '')}-${new Date().toISOString().split('T')[0]}.csv`;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            document.body.removeChild(a);
                            showSuccess('결제 내역이 성공적으로 내보내졌습니다.');
                        } else {
                            // JSON 응답
                            const result = await response.json();
                            if (result.success && result.download_url) {
                                window.open(result.download_url, '_blank');
                                showSuccess('결제 내역 내보내기가 완료되었습니다.');
                            } else {
                                throw new Error(result.message || '결제 내역 내보내기에 실패했습니다.');
                            }
                        }
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '결제 내역 내보내기에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('결제 내역 내보내기 오류:', error);
                    showError(error.message || '결제 내역 내보내기 중 오류가 발생했습니다.');
                } finally {
                    exportBillingHistoryBtn.disabled = false;
                    exportBillingHistoryBtn.textContent = '내보내기';
                }
            }

            /**
             * 결제 수단 편집
             */
            window.editPaymentMethod = function(paymentMethodId) {
                showError('결제 수단 편집 기능은 곧 추가될 예정입니다.');
                // TODO: 결제 수단 편집 모달 표시 및 API 호출
            };
            
            /**
             * 결제 수단 삭제
             */
            window.deletePaymentMethod = async function(paymentMethodId) {
                if (!confirm('정말로 이 결제 수단을 삭제하시겠습니까?')) {
                    return;
                }
                
                try {
                    const response = await fetch(`/api/organizations/${currentOrganizationId}/payment-methods/${paymentMethodId}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    if (response.ok) {
                        const result = await response.json();
                        showSuccess('결제 수단이 성공적으로 삭제되었습니다.');
                        // 결제 데이터 새로고침
                        loadBillingData();
                    } else {
                        const error = await response.json();
                        throw new Error(error.message || '결제 수단 삭제에 실패했습니다.');
                    }
                    
                } catch (error) {
                    console.error('결제 수단 삭제 오류:', error);
                    showError(error.message || '결제 수단 삭제 중 오류가 발생했습니다.');
                }
            };

            /**
             * 페이지 변경
             */
            let currentPage = 1;
            let totalPages = 3;
            let itemsPerPage = 10;
            
            window.changePage = function(page) {
                if (page === 'prev') {
                    if (currentPage > 1) {
                        currentPage--;
                        updatePagination();
                        loadBillingHistoryPage(currentPage);
                    }
                } else if (page === 'next') {
                    if (currentPage < totalPages) {
                        currentPage++;
                        updatePagination();
                        loadBillingHistoryPage(currentPage);
                    }
                } else if (typeof page === 'number') {
                    currentPage = page;
                    updatePagination();
                    loadBillingHistoryPage(currentPage);
                }
            };
            
            /**
             * 페이지네이션 UI 업데이트
             */
            function updatePagination() {
                const paginationInfo = document.getElementById('paginationInfo');
                const pageButtons = document.querySelectorAll('.page-btn');
                const prevBtn = document.getElementById('prevPageBtn');
                const nextBtn = document.getElementById('nextPageBtn');
                
                // 정보 업데이트
                const startItem = (currentPage - 1) * itemsPerPage + 1;
                const endItem = Math.min(currentPage * itemsPerPage, currentBillingData?.total_items || 25);
                const totalItems = currentBillingData?.total_items || 25;
                
                if (paginationInfo) {
                    paginationInfo.textContent = `총 ${totalItems}건 중 ${startItem}-${endItem}건 표시`;
                }
                
                // 페이지 버튼 업데이트
                pageButtons.forEach(btn => {
                    const page = parseInt(btn.dataset.page);
                    if (page === currentPage) {
                        btn.className = 'px-3 py-2 bg-blue-600 text-white rounded-lg text-sm page-btn';
                    } else {
                        btn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 page-btn';
                    }
                });
                
                // 이전/다음 버튼 상태 업데이트
                if (prevBtn) {
                    if (currentPage === 1) {
                        prevBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed';
                        prevBtn.disabled = true;
                    } else {
                        prevBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500';
                        prevBtn.disabled = false;
                    }
                }
                
                if (nextBtn) {
                    if (currentPage === totalPages) {
                        nextBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed';
                        nextBtn.disabled = true;
                    } else {
                        nextBtn.className = 'px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700';
                        nextBtn.disabled = false;
                    }
                }
            }
            
            /**
             * 특정 페이지의 결제 내역 로드
             */
            async function loadBillingHistoryPage(page) {
                try {
                    const response = await fetch(`/api/test/organizations/${currentOrganizationId}/billing/history?page=${page}&per_page=${itemsPerPage}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        updateBillingHistory(data.billing_histories);
                        
                        // 페이지네이션 정보 업데이트
                        totalPages = Math.ceil(data.total / itemsPerPage);
                        if (currentBillingData) {
                            currentBillingData.total_items = data.total;
                        }
                        
                        updatePagination();
                    } else {
                        console.error('Failed to load billing history page:', response.status);
                    }
                } catch (error) {
                    console.error('Error loading billing history page:', error);
                }
            }

            /**
             * 성공 메시지 표시
             */
            function showSuccess(message) {
                // 간단한 알림 - 실제 프로젝트에서는 toast 라이브러리 사용 권장
                alert(message);
            }

            /**
             * 에러 메시지 표시
             */
            function showError(message) {
                // 간단한 알림 - 실제 프로젝트에서는 toast 라이브러리 사용 권장
                alert(message);
            }
        });
    </script>
</body>
</html>
