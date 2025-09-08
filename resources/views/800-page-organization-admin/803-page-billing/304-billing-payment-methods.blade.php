{{-- 결제 수단 --}}
<style>
.payment-method-item {
    transition: all 0.2s ease;
    cursor: grab;
}
.payment-method-item:active {
    cursor: grabbing;
}
.payment-method-item.dragging {
    transform: rotate(3deg);
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
    z-index: 1000;
}
.payment-method-placeholder {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}
.priority-btn:hover {
    transform: scale(1.1);
}
</style>

<div class="bg-white rounded-lg border border-gray-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-gray-900">결제 수단</h3>
        <button id="addPaymentMethodBtn" class="px-3 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
            + 결제 수단 추가
        </button>
    </div>

    <div class="text-sm text-gray-600 mb-3">
        결제 시도 시 1순위부터 차례로 결제를 시도합니다. 실패하면 다음 우선순위 카드로 결제됩니다.
    </div>

    <div id="paymentMethodsList" class="space-y-3">
        <!-- 등록된 결제 수단이 없을 때 표시 -->
        <div id="no-payment-methods" class="text-center py-8 text-gray-500">
            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
            <p class="text-sm">등록된 결제 수단이 없습니다.</p>
            <p class="text-xs text-gray-400 mt-1">결제 수단을 추가하여 자동 결제를 이용하세요.</p>
        </div>
    </div>

    <!-- 결제 우선순위 안내 -->
    <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
        <div class="flex items-start gap-2">
            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            <div>
                <h4 class="text-sm font-medium text-blue-800">결제 처리 방식</h4>
                <p class="text-sm text-blue-700 mt-1">
                    • 1순위 카드부터 차례로 결제 시도<br>
                    • 결제 실패 시 자동으로 다음 순위 카드로 결제<br>
                    • 모든 카드 결제 실패 시 결제 실패 처리
                </p>
            </div>
        </div>
    </div>
</div>