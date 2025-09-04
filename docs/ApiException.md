# ApiException 사용법

## 개요
모든 API 오류 처리를 간소화하는 통합 Exception 클래스

## 기본 사용법

```php
use App\Exceptions\ApiException;

// 기본 오류
throw new ApiException('오류 메시지', 400);

// 정적 메서드 사용 (권장)
throw ApiException::validation('검증 실패', $errors);
throw ApiException::notFound();
throw ApiException::unauthorized();
throw ApiException::forbidden();
throw ApiException::serverError();
throw ApiException::badRequest('잘못된 요청');
throw ApiException::tooManyRequests();
throw ApiException::conflict();
```

## Controller에서 사용

```php
try {
    // 비즈니스 로직
    if ($rateLimitExceeded) {
        throw ApiException::tooManyRequests('요청 제한 초과');
    }
    
    return response()->json(['success' => true]);
    
} catch (ApiException $e) {
    return $e->render(); // 자동 JSON 응답
} catch (Exception $e) {
    Log::error($e->getMessage());
    throw ApiException::serverError();
}
```

## FormRequest에서 사용

```php
protected function failedValidation(Validator $validator)
{
    throw ApiException::validation(
        '입력값 검증에 실패했습니다.',
        $validator->errors()->toArray()
    );
}
```

## 응답 형식

```json
{
    "success": false,
    "message": "오류 메시지",
    "errors": {...},  // validation 시에만
    "data": {...}     // 추가 데이터 시에만
}
```