<?php

namespace App\Http\CoreApi\OrganizationBilling\CreateBusinessInfo;

use App\Http\CoreApi\Controller as BaseController;
use App\Models\Organization;
use App\Models\BusinessInfo;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class Controller extends BaseController
{
    public function __invoke(Request $request, Organization $organization): JsonResponse
    {
        try {
            DB::beginTransaction();

            // 기존 사업자 정보가 있다면 업데이트, 없다면 생성
            $businessInfo = $organization->businessInfo;

            if ($businessInfo) {
                $businessInfo->update($request->validated());
                $message = '사업자 정보가 성공적으로 업데이트되었습니다.';
            } else {
                $businessInfo = BusinessInfo::create(array_merge(
                    $request->validated(),
                    ['organization_id' => $organization->id]
                ));
                $message = '사업자 정보가 성공적으로 등록되었습니다.';
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'id' => $businessInfo->id,
                    'business_name' => $businessInfo->business_name,
                    'business_registration_number' => $businessInfo->business_registration_number,
                    'formatted_business_number' => $businessInfo->getFormattedBusinessNumber(),
                    'representative_name' => $businessInfo->representative_name,
                    'business_type' => $businessInfo->business_type,
                    'business_item' => $businessInfo->business_item,
                    'postal_code' => $businessInfo->postal_code,
                    'address' => $businessInfo->address,
                    'detail_address' => $businessInfo->detail_address,
                    'full_address' => $businessInfo->getFullAddress(),
                    'phone' => $businessInfo->phone,
                    'fax' => $businessInfo->fax,
                    'email' => $businessInfo->email,
                    'has_complete_info' => $businessInfo->hasCompleteInfo(),
                    'created_at' => $businessInfo->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $businessInfo->updated_at->format('Y-m-d H:i:s'),
                ],
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => '사업자 정보 저장 중 오류가 발생했습니다.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
