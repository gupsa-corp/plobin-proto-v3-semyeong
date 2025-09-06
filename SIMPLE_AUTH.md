# 단순한 인증 시스템

## 핵심 철학
> **"인증이 너무 복잡해. 그냥 웹에서 쓰는게 있고 API 토큰을 받아서 쓰는게 있고"**

## 📋 두 가지만 지원!

### 1️⃣ 웹 세션 인증 (브라우저)
- Laravel 기본 세션 인증 사용
- `auth()->check()` 로 확인
- 로그인 페이지로 자동 리디렉션

### 2️⃣ API 토큰 인증 (API 클라이언트)
- `Authorization: Bearer {token}` 헤더
- Laravel Sanctum 토큰 사용
- JSON 응답으로 오류 처리

## 🔧 구조

```
app/Http/Middleware/
├── SimpleAuth.php        # 두 가지 인증을 하나로 처리
└── ApiRateLimit.php      # 단순한 Rate Limit

routes/api.php            # 단순한 라우팅
bootstrap/app.php         # 단순한 설정
```

## 🚀 사용법

### API 클라이언트 (토큰)
```javascript
// 로그인해서 토큰 받기
const response = await fetch('/api/auth/login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
});
const { token } = await response.json();

// 토큰으로 API 호출
const userResponse = await fetch('/api/auth/logout', {
    method: 'POST',
    headers: { 'Authorization': `Bearer ${token}` }
});
```

### 웹 브라우저 (세션)
```javascript
// 일반적인 폼 submit 또는 fetch
const response = await fetch('/api/auth/login', {
    method: 'POST',
    credentials: 'include',  // 쿠키/세션 포함
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ email, password })
});
```

## ⚡ SimpleAuth 미들웨어

```php
class SimpleAuth
{
    public function handle(Request $request, Closure $next)
    {
        // 1. API 토큰이 있으면 토큰 인증
        if ($this->hasApiToken($request)) {
            return $this->authenticateWithToken($request, $next);
        }
        
        // 2. 없으면 웹 세션 인증 확인
        if (auth()->check()) {
            return $next($request);
        }
        
        // 3. 둘 다 없으면 실패
        if ($request->expectsJson()) {
            throw ApiException::unauthorized();
        }
        return redirect()->route('login');
    }
}
```

**동작 방식:**
- API 토큰이 있으면 → 토큰 인증
- 토큰 없으면 → 웹 세션 확인
- 둘 다 없으면 → 실패 (JSON이면 401, 웹이면 리디렉션)

## 🛠️ 라우트 설정

```php
// routes/api.php - 매우 단순!
Route::prefix('auth')->group(function () {
    // 공개 API
    Route::post('/check-email', CheckEmailController::class)
        ->middleware('rate.limit:10,1');
    Route::post('/signup', SignupController::class)
        ->middleware('rate.limit:3,5');
    Route::post('/login', LoginController::class)
        ->middleware('rate.limit:5,1');
    
    // 인증 필요 (웹 세션 OR API 토큰)
    Route::post('/logout', LogoutController::class)
        ->middleware(['auth.web-or-token', 'rate.limit:10,1']);
});
```

## 📊 컨트롤러 로직

### 로그인
```php
public function __invoke(Request $request)
{
    $email = strtolower(trim($request->email));
    $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ApiException::unauthorized('로그인 실패');
    }

    // 토큰 생성 (API 클라이언트용)
    $user->tokens()->delete();
    $token = $user->createToken('auth-token')->plainTextToken;

    return $this->success([
        'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        'token' => $token  // API 클라이언트는 이걸 저장해서 사용
    ]);
}
```

### 로그아웃
```php
public function __invoke(Request $request)
{
    $user = $request->user();
    
    if ($user) {
        // API 토큰 있으면 삭제
        if ($user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        // 웹 세션도 로그아웃
        auth()->logout();
    }

    return $this->success(null, '로그아웃 완료');
}
```

## ✨ 장점

### 🎯 단순함
- **두 가지만 지원**: 웹 세션, API 토큰
- **하나의 미들웨어**: SimpleAuth가 모든 것 처리
- **복잡한 설정 없음**: bootstrap/app.php 매우 단순

### 🔄 호환성
- **웹 브라우저**: 기존 방식 그대로 동작
- **API 클라이언트**: Bearer 토큰으로 간단 인증
- **혼용 가능**: 같은 엔드포인트에서 둘 다 지원

### 🚀 확장성
- **새로운 API**: 라우트에 `simple.auth` 미들웨어만 추가
- **Rate Limit**: 호출 제한 회수 `rate.limit:횟수,시간` 으로 간단 설정 가능
