import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 기본 앱 파일
                'resources/css/app.css', 
                'resources/js/app.js',
                
                // 영역별 CSS/JS 번들
                'resources/css/landing.css',
                'resources/js/landing.js',
                
                'resources/css/service.css', 
                'resources/js/service.js',
                
                'resources/css/admin.css',
                'resources/js/admin.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        rollupOptions: {
            output: {
                // 영역별로 청크 분리
                manualChunks: {
                    'landing-vendor': ['resources/js/landing.js'],
                    'service-vendor': ['resources/js/service.js'],
                    'admin-vendor': ['resources/js/admin.js'],
                }
            }
        }
    }
});
