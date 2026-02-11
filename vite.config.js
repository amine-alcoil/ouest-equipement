import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Build output for production
            build: {
                outDir: 'public/build', // where Laravel expects assets
                manifest: true,
                emptyOutDir: true,
            },
        }),
    ],
});
