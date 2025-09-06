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