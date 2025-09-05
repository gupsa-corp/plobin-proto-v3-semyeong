{{-- Landing Page Styles --}}
<style>
/* 기본 테마 */
:root {
    --primary-50: #f0f9ff;
    --primary-500: #3b82f6;
    --primary-600: #2563eb;
    --primary-700: #1d4ed8;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-600: #4b5563;
    --gray-900: #111827;
}

/* 랜딩 페이지 전용 스타일 */
.landing-hero {
    background: linear-gradient(135deg, var(--primary-50) 0%, var(--gray-50) 100%);
}

.landing-cta {
    background: var(--primary-600);
    transition: background-color 0.3s ease;
}

.landing-cta:hover {
    background: var(--primary-700);
}

/* 랜딩 페이지 애니메이션 */
.fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* 반응형 */
@media (max-width: 768px) {
    .landing-hero {
        padding: 2rem 1rem;
    }
}
</style>

