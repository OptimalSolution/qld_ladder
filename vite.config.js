import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/app-frontend.css',
                'resources/js/app.js',
                'resources/js/app-frontend.js',
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~coreui': path.resolve(__dirname, 'node_modules/@coreui/coreui'),
            crypto: 'crypto-browserify'
        }
    },
    optimizeDeps: {
        esbuildOptions: {
            define: {
                global: 'globalThis'
            }
        }
    },
    define: {
        'process.env': {},
        'global': 'globalThis'
    },
    build: {
        commonjsOptions: {
            transformMixedEsModules: true
        }
    }
});
