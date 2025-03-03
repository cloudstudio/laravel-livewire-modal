import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'resources/dist',
        rollupOptions: {
            input: [
                'resources/js/modal.js',
            ],
            output: {
                entryFileNames: '[name].js',
                chunkFileNames: '[name].js',
                assetFileNames: '[name].[ext]'
            }
        }
    }
});
