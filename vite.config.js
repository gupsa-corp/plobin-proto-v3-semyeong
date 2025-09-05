import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 000-app-common
                'resources/css/000-app-common.css',
                'resources/js/000-app-common.js',
                'resources/js/000-bootstrap-common.js',
                'resources/js/alpine.js',

                // 100-landing-common
                'resources/css/100-landing-common.css',
                'resources/js/100-landing-common.js',

                // 200-auth-common
                'resources/css/200-auth-common.css',
                'resources/js/200-auth-common.js',

                // 300-service-common
                'resources/css/300-service-common.css',
                'resources/js/300-service-common.js',

                // 900-admin-common
                'resources/css/900-admin-common.css',
                'resources/js/900-admin-common.js'
            ],
            refresh: true,
            detectTls: false,
        }),
        tailwindcss(),
    ],
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost'
        }
    },
    build: {
        rollupOptions: {
            output: {
                // 영역별로 청크 분리
                manualChunks: {
                    'app-vendor': ['resources/js/000-app-common.js', 'resources/js/000-bootstrap-common.js'],
                    'landing-vendor': ['resources/js/100-landing-common.js'],
                    'auth-vendor': ['resources/js/200-auth-common.js'],
                    'service-vendor': ['resources/js/300-service-common.js'],
                    'admin-vendor': ['resources/js/900-admin-common.js'],
                }
            }
        }
    }
});
