import { defineConfig } from 'vite'

import { resolve } from 'node:path'

import autoprefixer from 'autoprefixer'

export default defineConfig({
  appType: 'custom',
  css: {
    devSourcemap: true,
    postcss: {
      plugins: [
        autoprefixer
      ],
    }
  },
  build: {
    target: ['es2015'],
    outDir: 'dist',
    emptyOutDir: true,
    cssCodeSplit: true,
    sourcemap: process.env.NODE_ENV == 'development' ? true : false,
    lib: {
      entry: [
        resolve(__dirname, 'src/scripts/main.js'),
        resolve(__dirname, 'src/scripts/critical.js'),
        resolve(__dirname, 'src/scripts/blocks.js'),
        resolve(__dirname, 'src/scripts/admin.js'),
        resolve(__dirname, 'src/scripts/storybook.js'),
      ],
      fileName: '[name]',
    },
    watch: (process.env.NODE_ENV != 'development') ? null : {
      exclude: 'node_modules/**',
      include: 'src/**',
    },
    rollupOptions: {
      output: {
        assetFileNames: (assetInfo) => {
          return assetInfo.name;
        },
      },
    },
  },
})
