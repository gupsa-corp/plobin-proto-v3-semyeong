# API 개발 가이드

프로젝트의 API 개발을 위한 종합 가이드입니다.

## 🏗️ API 아키텍처

### RESTful API 설계 원칙

1. **리소스 중심 설계**
   - URL은 동사가 아닌 명사 사용
   - 계층 구조로 리소스 표현
   - 일관된 네이밍 컨벤션

2. **HTTP 메서드 활용**
   ```
   GET    /api/users        # 사용자 목록 조회
   POST   /api/users        # 새 사용자 생성
   GET    /api/users/123    # 특정 사용자 조회
   PUT    /api/users/123    # 사용자 정보 수정
   DELETE /api/users/123    # 사용자 삭제
   ```

3. **상태 코드 표준**
   - 200: 성공
   - 201: 생성 성공
   - 400: 잘못된 요청
   - 401: 인증 실패
   - 403: 권한 없음
   - 404: 리소스 없음
   - 500: 서버 오류

## 🔐 인증 및 보안

### JWT 토큰 기반 인증
```php
// 토큰 생성
$token = auth()->user()->createToken('api-token')->plainTextToken;

// 토큰 검증 미들웨어
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'profile']);
});
```

### API 보안 체크리스트
- [ ] HTTPS 사용
- [ ] 입력 데이터 검증
- [ ] SQL 인젝션 방지
- [ ] XSS 방지
- [ ] CSRF 토큰 사용
- [ ] Rate Limiting 설정

## 📊 응답 형식 표준

### 성공 응답
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "message": "사용자 조회 성공"
}
```

### 에러 응답
```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "입력 데이터가 올바르지 않습니다",
    "details": {
      "email": ["이메일 형식이 올바르지 않습니다"]
    }
  }
}
```

## 🧪 API 테스트

### Function Browser 활용
1. `/sandbox/function-browser` 접속
2. API 엔드포인트를 함수로 래핑하여 테스트
3. 다양한 파라미터로 테스트 실행
4. 응답 결과 실시간 확인

### 테스트 시나리오
```php
// 예시: User API 테스트
public function testUserApi()
{
    $params = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password123'
    ];
    
    // POST 요청 테스트
    $response = $this->makeApiCall('POST', '/api/users', $params);
    
    return $this->successResponse($response);
}
```

## 📝 API 문서화

### OpenAPI (Swagger) 스펙 작성
```yaml
openapi: 3.0.0
info:
  title: Project API
  version: 1.0.0
paths:
  /api/users:
    get:
      summary: 사용자 목록 조회
      responses:
        '200':
          description: 성공
          content:
            application/json:
              schema:
                type: object
                properties:
                  success:
                    type: boolean
                  data:
                    type: array
```

### 자동 문서 생성
- Laravel API Resource 활용
- 주석 기반 문서 생성
- Postman Collection 내보내기

## 🚀 배포 및 모니터링

### 단계별 배포
1. **개발 환경:** `/sandbox/function-browser`에서 테스트
2. **스테이징:** API 통합 테스트
3. **프로덕션:** 점진적 배포

### 모니터링 지표
- 응답 시간
- 에러율
- 처리량 (TPS)
- 가용성

## 🔧 최적화 팁

### 성능 최적화
- 데이터베이스 쿼리 최적화
- 캐시 활용
- 페이지네이션 구현
- 압축 응답 사용

### 코드 품질
- 일관된 코딩 스타일
- 에러 핸들링 표준화
- 로깅 전략 수립
- 단위 테스트 작성