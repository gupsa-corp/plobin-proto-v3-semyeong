# Plobin Proto V3

Laravel 11 기반의 Plobin 프로토타입 프로젝트입니다.

## 기술 스택

- **Framework**: Laravel 11
- **Database**: SQLite
- **PHP**: 8.4.1
- **Composer**: 2.8.3

## 프로젝트 설정

### 요구사항

- PHP 8.4+
- Composer
- Node.js (18+)
- npm
- SQLite

### 설치 및 실행

1. 저장소 클론
```bash
git clone https://github.com/gupsa-corp/plobin-proto-v3.git
cd plobin-proto-v3
```

2. 의존성 설치
```bash
composer install
```

3. 환경 설정
```bash
cp .env.example .env
php artisan key:generate
```

4. 데이터베이스 설정
```bash
# SQLite 데이터베이스 파일이 이미 생성되어 있습니다
# 마이그레이션 실행 (선택사항)
php artisan migrate
```

5. 프론트엔드 자산 개발 서버 실행
```bash
# 새 터미널에서 Vite 개발 서버 실행
npm install
npm run dev
```

6. Laravel 개발 서버 실행
```bash
# 다른 터미널에서 Laravel 서버 실행
php artisan serve
```

Laravel 서버와 Vite 개발 서버가 각각 별도 포트에서 실행됩니다.

## 프로젝트 구조

```
plobin-proto-v3/
├── app/                    # 애플리케이션 로직
├── config/                 # 설정 파일들
├── database/
│   ├── database.sqlite     # SQLite 데이터베이스 파일
│   ├── migrations/         # 데이터베이스 마이그레이션
│   └── seeders/           # 데이터 시더
├── public/                 # 웹 루트
├── resources/              # 뷰, CSS, JS 파일들
├── routes/                 # 라우트 정의
└── storage/                # 로그, 캐시, 세션 파일들
```

## 개발 가이드

### 프론트엔드 개발

이 프로젝트는 Vite를 사용하여 CSS/JS 자산을 관리합니다.

#### 개발 모드
```bash
npm run dev    # Vite 개발 서버 실행 (실시간 빌드)
```

#### 프로덕션 빌드
```bash
npm run build  # 프로덕션용 빌드
```

#### CSS/JS 파일 구조
```
resources/
├── css/
│   ├── 000-app-common.css      # 공통 앱 스타일
│   ├── 100-landing-common.css  # 랜딩페이지 스타일  
│   ├── 200-auth-common.css     # 인증 페이지 스타일
│   ├── 300-service-common.css  # 서비스 스타일
│   └── 900-admin-common.css    # 관리자 스타일
└── js/
    ├── 000-app-common.js       # 공통 앱 기능
    ├── 000-bootstrap-common.js # Axios 등 기본 라이브러리
    ├── 100-landing-common.js   # 랜딩페이지 기능
    ├── 200-auth-common.js      # 인증 페이지 기능
    ├── 300-service-common.js   # 서비스 기능
    └── 900-admin-common.js     # 관리자 기능
```

### 데이터베이스

이 프로젝트는 SQLite를 사용합니다. 데이터베이스 파일은 `database/database.sqlite`에 위치합니다.

### 마이그레이션

새로운 마이그레이션 생성:
```bash
php artisan make:migration create_table_name
```

마이그레이션 실행:
```bash
php artisan migrate
```

### 시더

데이터베이스 시더 실행:
```bash
php artisan db:seed
```

## 라이선스

이 프로젝트는 MIT 라이선스 하에 있습니다.

## 문서 작성 지침

### ⚠️ 중요: 포트 번호 금지
**문서에 절대 포트 번호를 명시하지 마세요!**

❌ 잘못된 예시:
- `http://localhost:8000`
- `포트 3000에서 실행`
- `localhost:5173 접속`

✅ 올바른 예시:
- `로컬 서버에서 실행`
- `개발 서버 접속`
- `별도 포트에서 실행`

### 이유
- 포트 번호는 환경에 따라 달라질 수 있음
- 사용자별 설정이나 충돌로 변경될 수 있음
- 하드코딩된 포트 번호는 혼란을 야기함

## 기여하기

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request
