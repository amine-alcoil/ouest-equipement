import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 9000,
        strictPort: true,
        hmr: {
            host: 'ouest-equipement.up.railway.app',
            protocol: 'wss',
            clientPort: 443,
        },
        watch: {
            usePolling: true
        }
    },
});


