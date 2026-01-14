import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import FastGlob from 'fast-glob';

export default defineConfig({
    plugins: [
        laravel({
            input: FastGlob.sync([
                'resources/css/app.css',
                'resources/js/app.js',

                'resources/css/**/*.css',
                'resources/js/**/*.js',
            ], { dot: false }),
            refresh: true,
        }),
        tailwindcss({ optimize: true }),
    ],
    optimizeDeps: {
        force: true,
        include: [
            'axios',
        ],
    },
    build: {
        manifest: 'build-manifest.json',
        outDir: 'public/build',
        assetsDir: 'bundle',
        chunkSizeWarningLimit: 500,
        rollupOptions: {
            output: {
                manualChunks: {
                    axios: ['axios'],
                }
            },
        },
    },
});
