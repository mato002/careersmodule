import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Set base path for subdirectory deployment (e.g., /careers)
    // In development, leave undefined to work at root or any path
    // Only set base path in production if deploying to subdirectory
    base: process.env.VITE_APP_BASE || undefined,
});
