<?php

namespace App\Http\OrganizationBilling\DownloadReceipt;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Organization;
use App\Models\BillingHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Dompdf\Dompdf;
use Dompdf\Options;

class Controller extends BaseController
{
    public function __invoke(Request $request, Organization $organization): Response|JsonResponse
    {
        $billingHistoryId = $request->input('billing_history_id');
        
        // 결제 내역 조회
        $billingHistory = BillingHistory::with(['organization', 'subscription'])
            ->where('id', $billingHistoryId)
            ->where('organization_id', $organization->id)
            ->first();

        if (!$billingHistory) {
            return response()->json([
                'success' => false,
                'message' => '결제 내역을 찾을 수 없습니다.',
            ], 404);
        }

        // 결제가 완료되지 않은 경우
        if (!$billingHistory->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => '결제가 완료되지 않은 내역입니다.',
            ], 400);
        }

        // 사업자 정보 확인
        $businessInfo = $organization->businessInfo;
        if (!$businessInfo || !$businessInfo->hasCompleteInfo()) {
            return response()->json([
                'success' => false,
                'message' => '영수증 발급을 위해 사업자 정보를 먼저 등록해주세요.',
            ], 400);
        }

        try {
            // Toss Payments 영수증 URL이 있으면 리다이렉트
            if ($billingHistory->receipt_url) {
                return response()->json([
                    'success' => true,
                    'receipt_url' => $billingHistory->receipt_url,
                ]);
            }

            // 자체 영수증 PDF 생성
            $pdf = $this->generateReceiptPdf($billingHistory, $businessInfo);
            
            $filename = "receipt_{$billingHistory->order_id}_{$billingHistory->getFormattedDate()}.pdf";
            
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '영수증 생성 중 오류가 발생했습니다.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateReceiptPdf(BillingHistory $billingHistory, $businessInfo): Dompdf
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);

        $html = $this->getReceiptHtml($billingHistory, $businessInfo);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf;
    }

    private function getReceiptHtml(BillingHistory $billingHistory, $businessInfo): string
    {
        $organization = $billingHistory->organization;
        $subscription = $billingHistory->subscription;
        
        return "
        <!DOCTYPE html>
        <html lang='ko'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>영수증</title>
            <style>
                body { 
                    font-family: 'DejaVu Sans', sans-serif; 
                    font-size: 12px; 
                    line-height: 1.5;
                    color: #333;
                }
                .header { 
                    text-align: center; 
                    border-bottom: 2px solid #333; 
                    padding-bottom: 20px; 
                    margin-bottom: 30px;
                }
                .header h1 { 
                    margin: 0; 
                    font-size: 24px; 
                    font-weight: bold;
                }
                .section { 
                    margin-bottom: 25px; 
                }
                .section h3 { 
                    background-color: #f5f5f5; 
                    padding: 8px; 
                    margin: 0 0 10px 0; 
                    font-size: 14px;
                    border-left: 4px solid #007bff;
                }
                .info-table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    margin-bottom: 15px;
                }
                .info-table th, .info-table td { 
                    border: 1px solid #ddd; 
                    padding: 8px; 
                    text-align: left; 
                }
                .info-table th { 
                    background-color: #f8f9fa; 
                    font-weight: bold; 
                    width: 30%;
                }
                .amount { 
                    font-size: 16px; 
                    font-weight: bold; 
                    color: #007bff; 
                }
                .footer { 
                    margin-top: 50px; 
                    text-align: center; 
                    font-size: 10px; 
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class='header'>
                <h1>영수증 (Receipt)</h1>
                <p>발급일: " . now()->format('Y년 m월 d일 H:i') . "</p>
            </div>

            <div class='section'>
                <h3>사업자 정보</h3>
                <table class='info-table'>
                    <tr>
                        <th>상호명</th>
                        <td>{$businessInfo->business_name}</td>
                    </tr>
                    <tr>
                        <th>사업자등록번호</th>
                        <td>{$businessInfo->getFormattedBusinessNumber()}</td>
                    </tr>
                    <tr>
                        <th>대표자</th>
                        <td>{$businessInfo->representative_name}</td>
                    </tr>
                    <tr>
                        <th>주소</th>
                        <td>{$businessInfo->getFullAddress()}</td>
                    </tr>
                    " . ($businessInfo->phone ? "<tr><th>연락처</th><td>{$businessInfo->phone}</td></tr>" : "") . "
                </table>
            </div>

            <div class='section'>
                <h3>결제 정보</h3>
                <table class='info-table'>
                    <tr>
                        <th>주문번호</th>
                        <td>{$billingHistory->order_id}</td>
                    </tr>
                    <tr>
                        <th>상품명</th>
                        <td>{$billingHistory->description}</td>
                    </tr>
                    <tr>
                        <th>결제일시</th>
                        <td>" . ($billingHistory->approved_at ? $billingHistory->approved_at->format('Y년 m월 d일 H:i') : $billingHistory->requested_at->format('Y년 m월 d일 H:i')) . "</td>
                    </tr>
                    <tr>
                        <th>결제수단</th>
                        <td>" . ($billingHistory->card_company ? "{$billingHistory->card_company} {$billingHistory->card_number}" : $billingHistory->method) . "</td>
                    </tr>
                    <tr>
                        <th>결제상태</th>
                        <td>{$billingHistory->getStatusText()}</td>
                    </tr>
                </table>
            </div>

            <div class='section'>
                <h3>금액 정보</h3>
                <table class='info-table'>
                    <tr>
                        <th>결제금액</th>
                        <td class='amount'>" . number_format($billingHistory->amount) . "원</td>
                    </tr>
                    " . ($billingHistory->vat ? "<tr><th>부가세</th><td>" . number_format($billingHistory->vat) . "원</td></tr>" : "") . "
                </table>
            </div>

            <div class='footer'>
                <p>이 영수증은 " . config('app.name') . "에서 발급되었습니다.</p>
                <p>문의사항이 있으시면 고객센터로 연락해 주세요.</p>
            </div>
        </body>
        </html>";
    }
}