import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Alpine.js
                'resources/js/alpine.js',

                // JS files (only existing ones)
                'resources/js/300-service-common.js'
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
                    'alpine-vendor': ['resources/js/alpine.js'],
                    'service-vendor': ['resources/js/300-service-common.js']
                }
            }
        }
    }
});
