# 테스트 구조 가이드

## 개요

이 프로젝트의 테스트는 컨트롤러별로 분리된 구조를 따릅니다. 각 API 컨트롤러마다 독립적인 테스트 파일을 생성하여 유지보수성과 가독성을 향상시킵니다.

## 테스트 파일 명명 규칙

### **필수 명명 규칙**
- **컨트롤러부모 + Test.php**: 각 컨트롤러의 부모 디렉토리명 + "Test.php"
- **예시**: 
  - `CheckEmailTest.php` (CheckEmail 컨트롤러)
  - `SignupPlobinTest.php` (SignupPlobin 컨트롤러)
  - `LoginPlobinTest.php` (LoginPlobin 컨트롤러)
  - `LogoutPlobinTest.php` (LogoutPlobin 컨트롤러)

### **파일 위치**
- **위치**: `tests/Feature/` 디렉토리
- **네임스페이스**: `Tests\\Feature`

## 테스트 메서드 명명 규칙

### **필수 메서드명 규칙**
- **한글 언더스코어 형식**: `한글로_작성한다_테스트()`
- **명확한 의도 표현**: 테스트 목적을 한글로 명확히 표현
- **일관된 패턴**: 모든 테스트 메서드는 `_테스트()` 로 끝남
- **@test 어노테이션**: PHPUnit이 한글 메서드명을 인식하도록 필수

### **메서드명 예시**
```php
/**
 * @test
 */
public function 회원가입이_정상적으로_처리된다_테스트()

/**
 * @test
 */
public function 이메일_중복_체크가_올바르게_작동한다_테스트()

/**
 * @test
 */
public function 레이트_리밋_초과시_429_응답을_반환한다_테스트()
```

## 현재 테스트 파일 구조

### 1. CheckEmailTest.php
**테스트 대상**: `app/Http/AuthUser/CheckEmail/Controller.php`

**주요 테스트 케이스**:
- ✅ 이메일_체크가_정상적으로_작동한다_테스트
- ✅ 이메일_형식_검증이_올바르게_작동한다_테스트  
- ✅ 이메일_누락시_검증_오류가_발생한다_테스트
- ✅ 레이트_리밋이_정상적으로_작동한다_테스트

**Rate Limit**: 10회/분

### 2. SignupPlobinTest.php
**테스트 대상**: `app/Http/AuthUser/SignupPlobin/Controller.php`

**주요 테스트 케이스**:
- ✅ 회원가입이_정상적으로_처리된다_테스트
- ✅ 필수_필드_누락시_검증_오류가_발생한다_테스트
- ✅ 잘못된_이메일_형식에_대해_검증_오류가_발생한다_테스트
- ✅ 비밀번호_확인이_일치하지_않으면_검증_오류가_발생한다_테스트
- ✅ 회원가입_레이트_리밋이_정상적으로_작동한다_테스트

**Rate Limit**: 3회/5분

### 3. LoginPlobinTest.php
**테스트 대상**: `app/Http/AuthUser/LoginPlobin/Controller.php`

**주요 테스트 케이스**:
- ✅ 로그인이_정상적으로_처리된다_테스트
- ✅ 잘못된_이메일로_로그인시_인증_실패가_발생한다_테스트
- ✅ 잘못된_비밀번호로_로그인시_인증_실패가_발생한다_테스트
- ✅ 필수_필드_누락시_검증_오류가_발생한다_테스트
- ✅ 잘못된_이메일_형식에_대해_검증_오류가_발생한다_테스트
- ✅ 로그인_레이트_리밋이_정상적으로_작동한다_테스트

**Rate Limit**: 5회/5분

### 4. LogoutPlobinTest.php
**테스트 대상**: `app/Http/AuthUser/LogoutPlobin/Controller.php`

**주요 테스트 케이스**:
- ✅ 로그아웃이_정상적으로_처리된다_테스트
- ✅ 인증되지_않은_사용자의_로그아웃_요청시_인증_오류가_발생한다_테스트
- ✅ 잘못된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트
- ✅ 이미_무효화된_토큰으로_로그아웃_요청시_인증_오류가_발생한다_테스트
- ✅ Sanctum을_통한_로그아웃이_정상적으로_처리된다_테스트

**인증 필요**: Laravel Sanctum 토큰

## 테스트 작성 패턴

### **Given-When-Then 패턴**
모든 테스트는 다음 구조를 따릅니다:

```php
/**
 * @test
 */
public function 테스트_목적을_설명한다_테스트()
{
    // Given: 테스트 조건 설정
    $testData = [
        'field' => 'value'
    ];
    
    // When: 실제 행동 수행
    $response = $this->post('/api/endpoint', $testData);
    
    // Then: 결과 검증
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
}
```

### **공통 테스트 패턴**

#### 1. 성공 케이스
```php
// 정상적인 요청 처리 확인
$response->assertStatus(200); // 또는 201
$response->assertJson(['success' => true]);
```

#### 2. 검증 실패 케이스
```php
// 잘못된 입력값에 대한 검증 실패
$response->assertStatus(422);
$response->assertJson([
    'success' => false,
    'message' => '검증에 실패했습니다.'
]);
```

#### 3. 인증 실패 케이스
```php
// 인증되지 않은 요청
$response->assertStatus(401);
$response->assertJson([
    'success' => false,
    'message' => '인증이 필요합니다.'
]);
```

#### 4. Rate Limit 테스트
```php
// 정상 범위 내 요청 확인
for ($i = 0; $i < $normalLimit; $i++) {
    $response = $this->post('/api/endpoint', $validData);
    $this->assertEquals(200, $response->getStatusCode());
}

// Rate limit 초과시 429 응답
$response = $this->post('/api/endpoint', $validData);
$this->assertEquals(429, $response->getStatusCode());
```

## 테스트 실행

### **전체 테스트 실행**
```bash
php artisan test
```

### **특정 테스트 파일 실행**
```bash
php artisan test tests/Feature/CheckEmailTest.php
php artisan test tests/Feature/SignupPlobinTest.php
php artisan test tests/Feature/LoginPlobinTest.php
php artisan test tests/Feature/LogoutPlobinTest.php
```

### **특정 테스트 메서드 실행**
```bash
php artisan test --filter="이메일_체크가_정상적으로_작동한다_테스트"
```

## 데이터베이스 테스트

### **RefreshDatabase 사용**
모든 테스트 클래스는 `RefreshDatabase` trait를 사용하여 각 테스트마다 깨끗한 데이터베이스 상태를 보장합니다.

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestClass extends TestCase
{
    use RefreshDatabase;
    
    // 테스트 메서드들...
}
```

## 확장 가이드

### **새로운 API 컨트롤러 테스트 추가**

1. **테스트 파일 생성**: `tests/Feature/[컨트롤러부모]Test.php`
2. **클래스 구조 설정**:
   ```php
   <?php
   
   namespace Tests\Feature;
   
   use Tests\TestCase;
   use Illuminate\Foundation\Testing\RefreshDatabase;
   
   class NewControllerTest extends TestCase
   {
       use RefreshDatabase;
       
       /**
        * @test
        */
       public function 기능이_정상적으로_작동한다_테스트()
       {
           // 테스트 코드...
       }
   }
   ```

3. **필수 테스트 케이스 포함**:
   - 정상 처리 케이스
   - 검증 실패 케이스
   - Rate Limit 테스트 (해당되는 경우)
   - 인증 테스트 (인증이 필요한 경우)

### **테스트 케이스 확장**

기본 테스트 외에 다음과 같은 케이스들을 추가로 고려하세요:

- **Edge Case**: 경계값 테스트
- **Performance**: 응답 시간 테스트
- **Security**: 보안 취약점 테스트
- **Integration**: 다른 서비스와의 연동 테스트

## 모범 사례

1. **테스트 독립성**: 각 테스트는 독립적으로 실행 가능해야 함
2. **명확한 의도**: 테스트명만으로 테스트 목적이 명확해야 함
3. **적절한 단언**: 필요한 검증은 모두 포함하되, 과도하지 않게
4. **데이터 정리**: RefreshDatabase로 테스트간 데이터 오염 방지
5. **문서화**: 복잡한 테스트 로직은 주석으로 설명

---

이 가이드를 따라 일관성 있는 테스트 구조를 유지하고, 각 API의 품질을 보장하세요.