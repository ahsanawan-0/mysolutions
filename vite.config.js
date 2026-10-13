import { defineConfig } from 'vite';

// Vite only builds src/ → public/assets/build/. The PHP `public/` folder is the
// web root and must never be copied into the build output (publicDir: false).
export default defineConfig({
  publicDir: false,
  base: '/assets/build/',
  build: {
    outDir: 'public/assets/build',
    emptyOutDir: true,
    manifest: 'manifest.json',
    rollupOptions: {
      input: 'src/js/main.js',
    },
  },
  server: {
    port: 5173,
    strictPort: true,
    origin: 'http://localhost:5173',
    cors: true,
  },
});
