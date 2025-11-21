import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    port: 5174, // ou 5173, peu importe
    proxy: {
      '/api': {
        target: 'http://localhost:8000',
        changeOrigin: true,
        secure: false,
        // /api/trips -> /trips, /api/stats/analytic-codes -> /stats/analytic-codes
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
    },
  },
})
