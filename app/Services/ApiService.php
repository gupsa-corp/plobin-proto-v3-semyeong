<?php

namespace App\Services;

use App\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

abstract class ApiService
{
    /**
     * 안전한 트랜잭션 실행
     */
    protected function safeTransaction(callable $callback): mixed
    {
        try {
            return DB::transaction($callback);
        } catch (Exception $e) {
            throw ApiException::serverError('작업 처리 중 오류가 발생했습니다.');
        }
    }

    /**
     * 모델 존재 여부 확인
     */
    protected function findOrFail(string $modelClass, mixed $id): Model
    {
        $model = $modelClass::find($id);
        
        if (!$model) {
            throw ApiException::notFound('요청한 리소스를 찾을 수 없습니다.');
        }
        
        return $model;
    }

    /**
     * 중복 체크
     */
    protected function checkDuplicate(string $modelClass, string $field, mixed $value, mixed $exceptId = null): void
    {
        $query = $modelClass::where($field, $value);
        
        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }
        
        if ($query->exists()) {
            throw ApiException::conflict("이미 존재하는 {$field}입니다.");
        }
    }

    /**
     * 페이지네이션 데이터 변환
     */
    protected function formatPagination($paginator): array
    {
        return [
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'has_more_pages' => $paginator->hasMorePages()
            ]
        ];
    }
}