import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Public site (TailwindCSS v4)
                'resources/css/app.css',
                'resources/js/app.js',
                // Admin panel (Bootstrap 5 + AdminLTE 4)
                'resources/css/admin.css',
                'resources/js/admin.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
