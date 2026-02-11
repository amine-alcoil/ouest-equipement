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
        host: '0.0.0.0', // Bind to all network interfaces
        hmr: {
            host: 'localhost', // The host the client connects to
        },
        watch: {
            usePolling: true // Useful for some Docker setups (e.g., WSL2)
        }
    },
});


