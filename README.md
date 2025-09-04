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

5. 개발 서버 실행
```bash
php artisan serve
```

애플리케이션이 `http://localhost:8000`에서 실행됩니다.

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

## 기여하기

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request
