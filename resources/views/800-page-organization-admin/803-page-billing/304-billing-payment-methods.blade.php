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