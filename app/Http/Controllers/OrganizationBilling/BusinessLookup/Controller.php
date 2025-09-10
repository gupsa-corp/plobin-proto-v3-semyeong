<?php

namespace App\Http\Controllers\OrganizationBilling\BusinessLookup;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    public function __invoke(Request $request, ?Organization $organization = null): JsonResponse
    {
        try {
            // 입력값 검증 - null 또는 빈 문자열 체크
            $businessRegistrationNumber = $request->input('business_registration_number');

            if (empty($businessRegistrationNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => '사업자등록번호를 입력해주세요.',
                    'error' => 'BUSINESS_NUMBER_REQUIRED'
                ], 400);
            }

            $businessNumber = $this->formatBusinessNumber($businessRegistrationNumber);

            // 사업자등록번호 유효성 검사
            if (!$this->validateBusinessNumber($businessNumber)) {
                return response()->json([
                    'success' => false,
                    'message' => '올바르지 않은 사업자등록번호 형식입니다.',
                    'error' => 'INVALID_BUSINESS_NUMBER'
                ], 400);
            }

            // 국세청 사업자등록정보 진위확인 서비스 API 호출
            // 실제 구현에서는 국세청 API 키와 엔드포인트가 필요합니다
            $businessInfo = $this->lookupBusinessInfo($businessNumber);

            if (!$businessInfo) {
                return response()->json([
                    'success' => false,
                    'message' => '사업자등록번호 조회에 실패했습니다. 번호를 다시 확인해주세요.',
                    'error' => 'BUSINESS_NOT_FOUND'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => '사업자 정보 조회가 완료되었습니다.',
                'data' => [
                    'business_registration_number' => $businessNumber,
                    'formatted_business_number' => $this->formatBusinessNumberForDisplay($businessNumber),
                    'business_name' => $businessInfo['business_name'] ?? '',
                    'representative_name' => $businessInfo['representative_name'] ?? '',
                    'business_status' => $businessInfo['business_status'] ?? '',
                    'business_type' => $businessInfo['business_type'] ?? '',
                    'business_item' => $businessInfo['business_item'] ?? '',
                    'address' => $businessInfo['address'] ?? '',
                    'is_valid' => $businessInfo['is_valid'] ?? false,
                    'lookup_date' => now()->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Business lookup error', [
                'business_number' => $request->business_registration_number ?? '',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => '사업자 조회 중 시스템 오류가 발생했습니다.',
                'error' => 'SYSTEM_ERROR'
            ], 500);
        }
    }

    /**
     * 사업자등록번호 포맷팅 (숫자만 추출)
     */
    private function formatBusinessNumber(string $businessNumber): string
    {
        return preg_replace('/[^0-9]/', '', $businessNumber);
    }

    /**
     * 사업자등록번호 유효성 검사
     */
    private function validateBusinessNumber(string $businessNumber): bool
    {
        // 10자리 숫자인지 확인
        if (strlen($businessNumber) !== 10 || !ctype_digit($businessNumber)) {
            return false;
        }

        // 개발 환경에서는 유효성 검사를 완화
        if (app()->environment('local')) {
            return true;
        }

        // 체크섬 검증 (한국 사업자등록번호 공식)
        $weights = [1, 3, 7, 1, 3, 7, 1, 3, 5, 1];
        $sum = 0;

        // 9번째 자리까지 계산
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($businessNumber[$i]) * $weights[$i];
        }

        // 8번째 자리에 5를 곱한 후 10으로 나눈 몫을 더함
        $sum += intval($businessNumber[8]) * 5 / 10;

        $remainder = $sum % 10;
        $checkDigit = $remainder === 0 ? 0 : 10 - $remainder;

        return intval($businessNumber[9]) === $checkDigit;
    }

    /**
     * 표시용 사업자등록번호 포맷팅 (123-45-67890)
     */
    private function formatBusinessNumberForDisplay(string $businessNumber): string
    {
        if (strlen($businessNumber) === 10) {
            return substr($businessNumber, 0, 3) . '-' . substr($businessNumber, 3, 2) . '-' . substr($businessNumber, 5);
        }
        return $businessNumber;
    }

    /**
     * 실제 사업자등록정보 조회
     *
     * 실제 구현에서는 국세청 사업자등록정보 진위확인 서비스 API를 사용해야 합니다.
     * 현재는 더미 데이터를 반환합니다.
     */
    private function lookupBusinessInfo(string $businessNumber): ?array
    {
        // 개발 환경에서는 더미 데이터 반환
        if (app()->environment('local')) {
            return $this->getDummyBusinessInfo($businessNumber);
        }

        // 실제 API 호출 (국세청 사업자등록정보 진위확인 서비스)
        try {
            // 국세청 API 설정 (실제 키와 URL 필요)
            $apiKey = config('services.nts.api_key');
            $apiUrl = config('services.nts.business_lookup_url');

            if (!$apiKey || !$apiUrl) {
                Log::warning('NTS API configuration missing');
                return $this->getDummyBusinessInfo($businessNumber);
            }

            $response = Http::timeout(10)
                ->post($apiUrl, [
                    'api_key' => $apiKey,
                    'business_registration_number' => $businessNumber,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'business_name' => $data['business_name'] ?? '',
                    'representative_name' => $data['representative_name'] ?? '',
                    'business_status' => $data['business_status'] ?? '',
                    'business_type' => $data['business_type'] ?? '',
                    'business_item' => $data['business_item'] ?? '',
                    'address' => $data['address'] ?? '',
                    'is_valid' => $data['is_valid'] ?? false,
                ];
            }

            Log::error('NTS API call failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('NTS API exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * 개발용 더미 데이터
     */
    private function getDummyBusinessInfo(string $businessNumber): array
    {
        $dummyData = [
            '1234567890' => [
                'business_name' => '(주)테스트컴퍼니',
                'representative_name' => '홍길동',
                'business_status' => '계속사업자',
                'business_type' => '서비스업',
                'business_item' => '소프트웨어 개발 및 공급업',
                'address' => '서울특별시 강남구 테헤란로 123',
                'is_valid' => true,
            ],
            '9876543210' => [
                'business_name' => '개발자카페',
                'representative_name' => '김개발',
                'business_status' => '계속사업자',
                'business_type' => '도매 및 소매업',
                'business_item' => '커피전문점 운영업',
                'address' => '부산광역시 해운대구 센텀로 456',
                'is_valid' => true,
            ],
        ];

        return $dummyData[$businessNumber] ?? [
            'business_name' => '조회된 사업체명',
            'representative_name' => '대표자명',
            'business_status' => '계속사업자',
            'business_type' => '서비스업',
            'business_item' => '기타 서비스업',
            'address' => '주소 정보',
            'is_valid' => true,
        ];
    }
}
