# ApiException 사용법

## 개요
모든 API 오류 처리를 간소화하는 통합 Exception 클래스

## 기본 사용법

```php
use App\Exceptions\ApiException;

// 기본 오류
throw new ApiException('오류 메시지', 400);

// 정적 메서드 사용 (필수)
throw ApiException::validation('검증 실패', $errors);
throw ApiException::notFound();
throw ApiException::unauthorized();
throw ApiException::forbidden();
throw ApiException::serverError();
throw ApiException::badRequest('잘못된 요청');
throw ApiException::tooManyRequests();
throw ApiException::conflict();
```
