{{-- 결제 내역 --}}
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">결제 내역</h3>
            <div class="flex gap-2">
                <select id="periodFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="6months" {{ $filters['period'] === '6months' ? 'selected' : '' }}>최근 6개월</option>
                    <option value="1year" {{ $filters['period'] === '1year' ? 'selected' : '' }}>최근 1년</option>
                    <option value="all" {{ $filters['period'] === 'all' ? 'selected' : '' }}>전체</option>
                </select>
                <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="all" {{ $filters['status'] === 'all' ? 'selected' : '' }}>모든 상태</option>
                    <option value="completed" {{ $filters['status'] === 'completed' ? 'selected' : '' }}>결제 완료</option>
                    <option value="failed" {{ $filters['status'] === 'failed' ? 'selected' : '' }}>결제 실패</option>
                    <option value="refunded" {{ $filters['status'] === 'refunded' ? 'selected' : '' }}>환불 완료</option>
                    <option value="pending" {{ $filters['status'] === 'pending' ? 'selected' : '' }}>결제 대기</option>
                </select>
                <button id="exportBillingHistoryBtn" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200">
                    내보내기
                </button>
            </div>
        </div>
        
        <!-- 검색 및 필터 -->
        <div class="mt-4 flex items-center gap-4">
            <div class="flex-1">
                <input type="text" id="searchInput" placeholder="결제 내역 검색..." value="{{ $filters['search'] }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="text-sm text-gray-500">
                총 <span id="totalRecords">{{ $billingHistories->total() }}</span>건
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">날짜</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">설명</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">결제 수단</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">금액</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">상태</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">액션</th>
                </tr>
            </thead>
            <tbody id="billing-history-tbody" class="bg-white divide-y divide-gray-200">
                @forelse($billingHistories as $history)
                    <tr class="hover:bg-gray-50 cursor-pointer" onclick="showBillingDetail('{{ $history->id }}')">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $history->getFormattedDate() }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $history->description }}</div>
                                <div class="text-sm text-gray-500">
                                    @if($history->subscription)
                                        {{ $history->subscription->plan_name }} 
                                    @endif
                                    @if($history->method)
                                        · {{ $history->method }}
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                @if($history->card_company && $history->card_number)
                                    <div class="w-6 h-4 rounded flex items-center justify-center
                                        {{ $history->card_company === 'VISA' ? 'bg-blue-600' : 
                                           ($history->card_company === 'Mastercard' ? 'bg-red-600' : 'bg-gray-600') }}">
                                        <span class="text-white text-xs font-bold">
                                            {{ substr($history->card_company, 0, 1) }}
                                        </span>
                                    </div>
                                    <span class="text-sm text-gray-600">**** {{ substr($history->card_number, -4) }}</span>
                                @else
                                    <div class="w-6 h-4 bg-gray-400 rounded flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">-</span>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $history->method ?? '결제 대기' }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $history->getFormattedAmount() }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                bg-{{ $history->getStatusBadgeColor() }}-100 text-{{ $history->getStatusBadgeColor() }}-800">
                                {{ $history->getStatusText() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex gap-2 justify-end">
                                @if($history->isPaid())
                                    <button onclick="event.stopPropagation(); downloadSpecificReceipt('{{ $history->id }}')" class="text-blue-600 hover:text-blue-900">영수증</button>
                                @elseif($history->isCanceled() || $history->isExpired())
                                    <button onclick="event.stopPropagation(); retryPayment('{{ $history->id }}')" class="text-orange-600 hover:text-orange-900">재시도</button>
                                @elseif($history->status === 'READY')
                                    <button onclick="event.stopPropagation(); processPayment('{{ $history->id }}')" class="text-green-600 hover:text-green-900">결제하기</button>
                                @endif
                                <button onclick="event.stopPropagation(); showBillingDetail('{{ $history->id }}')" class="text-gray-600 hover:text-gray-900">상세보기</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">결제 내역이 없습니다.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- 페이지네이션 --}}
    @if($billingHistories->hasPages())
        <div id="paginationContainer" class="px-6 py-3 flex items-center justify-between border-t border-gray-200">
            <div id="paginationInfo" class="text-sm text-gray-500">
                총 {{ $billingHistories->total() }}건 중 {{ $billingHistories->firstItem() ?? 0 }}-{{ $billingHistories->lastItem() ?? 0 }}건 표시
            </div>
            <div id="paginationButtons" class="flex gap-1">
                @if($billingHistories->onFirstPage())
                    <button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">이전</button>
                @else
                    <button onclick="changePage({{ $billingHistories->currentPage() - 1 }})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-500 hover:bg-gray-50">이전</button>
                @endif

                @foreach($billingHistories->getUrlRange(max(1, $billingHistories->currentPage() - 2), min($billingHistories->lastPage(), $billingHistories->currentPage() + 2)) as $page => $url)
                    @if($page == $billingHistories->currentPage())
                        <button class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm page-btn" data-page="{{ $page }}">{{ $page }}</button>
                    @else
                        <button onclick="changePage({{ $page }})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50 page-btn" data-page="{{ $page }}">{{ $page }}</button>
                    @endif
                @endforeach

                @if($billingHistories->hasMorePages())
                    <button onclick="changePage({{ $billingHistories->currentPage() + 1 }})" class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">다음</button>
                @else
                    <button disabled class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">다음</button>
                @endif
            </div>
        </div>
    @endif
</div>