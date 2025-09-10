# API 아키텍처 가이드

## 개요
수백 개의 API를 효율적으로 개발하기 위한 간소화된 아키텍처 패턴

## 명명 규칙 (중요!)

### **필수 파일명 규칙**
- **Controller.php** - 컨트롤러는 반드시 `Controller`로 명명
- **Request.php** - 요청 검증은 반드시 `Request`로 명명

### **금지사항**
❌ `SimplifiedController.php`  
❌ `UserCreateController.php`  
❌ `CheckEmailController.php`  
❌ `CreateUserRequest.php`  

### **올바른 예시**
✅ `app/Http/Controllers/User/Create/Controller.php`  
✅ `app/Http/Controllers/User/Create/Request.php`  
✅ `app/Http/Controllers/AuthUser/CheckEmail/Controller.php`  
✅ `app/Http/Controllers/AuthUser/CheckEmail/Request.php`  

**중요**: 모든 API 컨트롤러는 `app/Http/Controllers/` 하위에 위치해야 함. `Controllers`나 `Requests` 폴더는 사용하지 않음.

**이유**: 네임스페이스로 기능을 구분하고, 파일명은 표준화하여 일관성 유지
