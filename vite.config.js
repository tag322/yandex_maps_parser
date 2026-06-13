import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite';
import path from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/src/main.js'],
            refresh: true,
        }),
        tailwindcss(),
        vue()
    ],
    resolve: {
        alias: {
        '@': path.resolve(__dirname, 'resources/js/src'),
        }
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
