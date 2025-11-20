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

// import { fileURLToPath, URL } from 'node:url'
// import { defineConfig } from 'vite'
// import vue from '@vitejs/plugin-vue'

// // https://vitejs.dev/config/
// export default defineConfig({
//   plugins: [vue()],
//   resolve: {
//     alias: {
//       '@': fileURLToPath(new URL('./src', import.meta.url)),
//     },
//   },
//   server: {
//     proxy: {
//       // Tout ce qui commence par /api sera redirigé vers le backend Symfony
//       '/api': {
//         target: 'http://localhost:8000',
//         changeOrigin: true,
//         secure: false,
//         // IMPORTANT : on enlève le prefix /api pour que Symfony voie /trips
//         rewrite: (path) => path.replace(/^\/api/, ''),
//       },
//     },
//   },
// })
