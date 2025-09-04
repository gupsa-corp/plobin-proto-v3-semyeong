# API 아키텍처 가이드

## 개요
수백 개의 API를 효율적으로 개발하기 위한 간소화된 아키텍처 패턴

## 핵심 구성요소

### 1. 계층별 기본 클래스
- **ApiController**: 공통 응답 메서드와 에러 처리
- **ApiRequest**: 공통 검증 규칙과 전처리
- **ApiService**: 비즈니스 로직 처리
- **ApiException**: 통합 예외 처리

### 2. 기능별 Traits
- **HasRateLimit**: Rate limiting 기능
- **HasCache**: 캐싱 기능  
- **HasSecurity**: 보안 관련 기능

### 3. 유틸리티
- **ApiHelper**: 공통 도구 모음

## 사용 패턴

### 기본 Controller 패턴 (극도로 간소화!)
```php
class UserController extends ApiController
{
    use HasRateLimit, HasCache, HasSecurity;

    public function store(CreateUserRequest $request)
    {
        $this->checkRateLimit($request, null, 5, 1); // 5회/분
        
        $user = User::create($request->validated());
        
        return $this->created($user, '사용자가 생성되었습니다.');
    }
}
```

**주요 개선점**:
- ❌ `safeExecute` 제거 (global exception handler가 처리)
- ❌ `try-catch` 제거 (자동 처리)
- ❌ `function() use ($request)` 제거

### 기본 Request 패턴
```php
class CreateUserRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email' => $this->emailRules(),
            'password' => $this->passwordRules(),
            'name' => $this->nameRules()
        ];
    }
}
```

### Service Layer 패턴
```php
class UserService extends ApiService
{
    public function createUser(array $data): User
    {
        return $this->safeTransaction(function() use ($data) {
            
            $this->checkDuplicate(User::class, 'email', $data['email']);
            
            return User::create($data);
        });
    }
}
```

## 코드 간소화 효과

### Before (기존 방식)
```php
// Controller: 60+ lines
// Request: 50+ lines  
// Response: 50+ lines
// Total: 160+ lines
```

### After (극도로 간소화된 방식)
```php
// Controller: 8 lines (!!)
// Request: 10 lines
// Total: 18 lines (-89% 감소)
```

## API 개발 플로우

1. **Controller 생성**: `ApiController` 상속
2. **Request 생성**: `ApiRequest` 상속, 필요한 규칙만 정의
3. **Traits 추가**: 필요한 기능별 trait 사용
4. **Service Layer**: 복잡한 로직은 별도 service로 분리
5. **에러 처리**: `ApiException` 자동 처리

## 표준 응답 형식

### 성공 응답
```json
{
    "success": true,
    "message": "성공",
    "data": {...}
}
```

### 실패 응답
```json
{
    "success": false,
    "message": "오류 메시지",
    "errors": {...}  // validation 시에만
}
```

## 명명 규칙 (중요!)

### **필수 파일명 규칙**
- **Controller.php** - 컨트롤러는 반드시 `Controller`로 명명
- **Request.php** - 요청 검증은 반드시 `Request`로 명명  
- **Response.php** - 응답 처리는 반드시 `Response`로 명명 (더 이상 사용하지 않음)

### **금지사항**
❌ `SimplifiedController.php`  
❌ `UserCreateController.php`  
❌ `CheckEmailController.php`  
❌ `CreateUserRequest.php`  

### **올바른 예시**
✅ `app/Http/User/Create/Controller.php`  
✅ `app/Http/User/Create/Request.php`  
✅ `app/Http/AuthUser/CheckEmail/Controller.php`  
✅ `app/Http/AuthUser/CheckEmail/Request.php`  

**이유**: 네임스페이스로 기능을 구분하고, 파일명은 표준화하여 일관성 유지

## 테스트 코드 명명 규칙

### **필수 테스트 메서드명 규칙**
- **한글 언더스코어 형식**: `한글로_작성한다_테스트()`
- **명확한 의도 표현**: 테스트 목적을 한글로 명확히 표현
- **일관된 패턴**: 모든 테스트 메서드는 `_테스트()` 로 끝남

### **테스트 메서드명 예시**
```php
// ✅ 올바른 예시
public function 사용자_생성이_정상적으로_동작한다_테스트()
public function 이메일_중복_체크가_올바르게_작동한다_테스트()
public function 레이트_리밋_초과시_429_응답을_반환한다_테스트()
public function 잘못된_입력값에_대해_422_검증_오류를_반환한다_테스트()

// ❌ 금지된 예시
public function test_user_creation_works()
public function testEmailDuplicateCheck()
public function userCreationTest()
```

### **테스트 작성 규칙**
- **Given-When-Then 패턴**: 상황 설정 → 액션 → 결과 검증
- **명확한 주석**: 각 단계별로 한글 주석 작성
- **의미 있는 변수명**: 테스트 목적이 명확히 드러나는 변수명 사용

### **테스트 예시**
```php
/**
 * 이메일 중복 체크가 정상 동작하는지 테스트
 * @test
 */
public function 이메일_중복_체크가_정상_동작한다_테스트()
{
    // Given: 테스트용 이메일 준비
    $email = 'test@gmail.com';
    
    // When: 이메일 중복 체크 API 호출
    $response = $this->post('/api/auth/check-email', ['email' => $email]);
    
    // Then: 성공 응답 확인
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
}
```

**중요**: PHPUnit이 한글 메서드명을 인식하려면 `@test` 어트리뷰트가 필수입니다.

## 장점

- **개발 속도 향상**: 89% 코드 감소
- **일관성**: 표준화된 패턴과 명명 규칙
- **유지보수성**: 중앙화된 로직
- **확장성**: 쉬운 기능 추가
- **안정성**: 검증된 에러 처리