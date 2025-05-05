import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',

            ],
            refresh: [
                'resources/views/**',
                'resources/js/Pages/**',
            ],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),

        },
    },
    optimizeDeps: {
        include: ['ziggy-js'],
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
