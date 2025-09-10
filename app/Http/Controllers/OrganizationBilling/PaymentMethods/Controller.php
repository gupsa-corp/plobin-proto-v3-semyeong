<?php

namespace App\Http\Controllers\OrganizationBilling\PaymentMethods;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\PaymentMethod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Controller extends BaseController
{
    /**
     * 결제 수단 목록 조회
     */
    public function index(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentMethods = $organization->paymentMethods()
            ->active()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $paymentMethods->map(function ($method) {
                return [
                    'id' => $method->id,
                    'display_name' => $method->getDisplayName(),
                    'method_type' => $method->method_type,
                    'card_company' => $method->card_company,
                    'card_number' => $method->card_number,
                    'expiry_date' => $method->getExpiryDate(),
                    'cardholder_name' => $method->cardholder_name,
                    'is_default' => $method->is_default,
                    'is_active' => $method->is_active,
                    'is_expired' => $method->isExpired(),
                    'created_at' => $method->created_at->format('Y.m.d'),
                ];
            })
        ]);
    }

    /**
     * 결제 수단 추가
     */
    public function store(Request $request, Organization $organization): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'card_type' => 'required|string|max:50',
            'card_number' => 'required|string|max:19',
            'expiry_date' => 'required|string|max:5',
            'cvv' => 'required|string|max:4',
            'cardholder_name' => 'required|string|max:100',
            'set_as_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // 기본 결제 수단으로 설정하는 경우, 기존 기본 결제 수단 해제
        $isDefault = $request->boolean('set_as_default');
        if ($isDefault) {
            $organization->paymentMethods()
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }

        // 첫 번째 결제 수단인 경우 자동으로 기본으로 설정
        if (!$isDefault && $organization->paymentMethods()->active()->count() === 0) {
            $isDefault = true;
        }

        $paymentMethod = $organization->paymentMethods()->create([
            'method_type' => 'card',
            'card_company' => $request->card_type,
            'card_number' => $this->maskCardNumber($request->card_number),
            'expiry_month' => substr($request->expiry_date, 0, 2),
            'expiry_year' => '20' . substr($request->expiry_date, 3, 2),
            'cardholder_name' => $request->cardholder_name,
            'is_default' => $isDefault,
            'is_active' => true,
            // 실제 구현에서는 CVV는 저장하지 않고 결제 시에만 사용
        ]);

        return response()->json([
            'success' => true,
            'message' => '결제 수단이 성공적으로 추가되었습니다.',
            'data' => [
                'id' => $paymentMethod->id,
                'display_name' => $paymentMethod->getDisplayName(),
                'method_type' => $paymentMethod->method_type,
                'card_company' => $paymentMethod->card_company,
                'card_number' => $paymentMethod->card_number,
                'expiry_date' => $paymentMethod->getExpiryDate(),
                'cardholder_name' => $paymentMethod->cardholder_name,
                'is_default' => $paymentMethod->is_default,
                'is_active' => $paymentMethod->is_active,
                'created_at' => $paymentMethod->created_at->format('Y.m.d'),
            ]
        ]);
    }

    /**
     * 결제 수단 수정
     */
    public function update(Request $request, Organization $organization, $paymentMethodId): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentMethod = $organization->paymentMethods()->findOrFail($paymentMethodId);

        $validator = Validator::make($request->all(), [
            'card_type' => 'sometimes|string|max:50',
            'card_number' => 'sometimes|string|max:19',
            'expiry_date' => 'sometimes|string|max:5',
            'cardholder_name' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = [];
        if ($request->has('card_type')) {
            $updateData['card_company'] = $request->card_type;
        }
        if ($request->has('card_number')) {
            $updateData['card_number'] = $this->maskCardNumber($request->card_number);
        }
        if ($request->has('expiry_date')) {
            $updateData['expiry_month'] = substr($request->expiry_date, 0, 2);
            $updateData['expiry_year'] = '20' . substr($request->expiry_date, 3, 2);
        }
        if ($request->has('cardholder_name')) {
            $updateData['cardholder_name'] = $request->cardholder_name;
        }

        $paymentMethod->update($updateData);

        return response()->json([
            'success' => true,
            'message' => '결제 수단이 성공적으로 수정되었습니다.',
            'data' => [
                'id' => $paymentMethod->id,
                'display_name' => $paymentMethod->getDisplayName(),
                'method_type' => $paymentMethod->method_type,
                'card_company' => $paymentMethod->card_company,
                'card_number' => $paymentMethod->card_number,
                'expiry_date' => $paymentMethod->getExpiryDate(),
                'cardholder_name' => $paymentMethod->cardholder_name,
                'is_default' => $paymentMethod->is_default,
                'is_active' => $paymentMethod->is_active,
            ]
        ]);
    }

    /**
     * 결제 수단 삭제
     */
    public function destroy(Request $request, Organization $organization, $paymentMethodId): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentMethod = $organization->paymentMethods()->findOrFail($paymentMethodId);

        // 기본 결제 수단이면서 다른 활성 결제 수단이 있는 경우, 다른 결제 수단을 기본으로 설정
        if ($paymentMethod->is_default) {
            $otherMethod = $organization->paymentMethods()
                ->active()
                ->where('id', '!=', $paymentMethodId)
                ->first();

            if ($otherMethod) {
                $otherMethod->update(['is_default' => true]);
            }
        }

        $paymentMethod->update(['is_active' => false]);

        return response()->json([
            'success' => true,
            'message' => '결제 수단이 성공적으로 삭제되었습니다.'
        ]);
    }

    /**
     * 기본 결제 수단 설정
     */
    public function setDefault(Request $request, Organization $organization, $paymentMethodId): JsonResponse
    {
        // 권한 체크 (현재 사용자가 해당 조직의 관리자인지)
        $user = $request->user();
        if ($user && !$organization->hasMember($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $paymentMethod = $organization->paymentMethods()->active()->findOrFail($paymentMethodId);

        // 기존 기본 결제 수단 해제
        $organization->paymentMethods()
            ->where('is_default', true)
            ->update(['is_default' => false]);

        // 새로운 기본 결제 수단 설정
        $paymentMethod->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => '기본 결제 수단이 변경되었습니다.',
            'data' => [
                'id' => $paymentMethod->id,
                'display_name' => $paymentMethod->getDisplayName(),
                'is_default' => true,
            ]
        ]);
    }

    /**
     * 카드 번호 마스킹 처리
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $cardNumber = preg_replace('/\D/', '', $cardNumber); // 숫자만 추출
        $length = strlen($cardNumber);

        if ($length < 4) {
            return $cardNumber;
        }

        return str_repeat('*', $length - 4) . substr($cardNumber, -4);
    }
}
