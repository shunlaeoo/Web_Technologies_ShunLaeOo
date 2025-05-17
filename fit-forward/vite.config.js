import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  base: '/Web_Technologies_ShunLaeOo/',
  plugins: [
    react(),
  ],
  build: {
    target: 'es2019',
    sourcemap: true
  }
})
