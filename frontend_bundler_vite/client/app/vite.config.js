import { resolve } from 'node:path'
import { defineConfig } from 'vite'
import { minify } from 'terser'
import autoprefixer from 'autoprefixer'

function minifyBundles() {
  return {
    name: 'minifyBundles',

    // async writeBundle(options, bundle) {
    //   for (let key in bundle) {
    //     if (bundle[key].type == 'chunk' && key.endsWith('.js')) {
    //       console.log(key, bundle[key].type);
    //       const minifyCode = await minify(bundle[key].code, { sourceMap: false })
    //       bundle[key].code = minifyCode.code
    //     }
    //   }
    //   return bundle
    // },

    // async transform(code, id) {
    //   let result = code
    //   if (id.endsWith('.js')) {
    //     const minifyCode = await minify(code, { sourceMap: false })
    //     result = minifyCode.code
    //   }
    //   return result
    // },

    async generateBundle(options, bundle) {
      for (let key in bundle) {
        if (bundle[key].type == 'chunk' && key.endsWith('.js')) {
          const minifyCode = await minify(bundle[key].code, {
            compress: true,
            mangle: true,
            sourceMap: false,
          })
          bundle[key].code = minifyCode.code
        }
      }
      return bundle
    },
  }
}

export default defineConfig({
  appType: 'custom',
  css: {
    devSourcemap: true,
    postcss: {
      plugins: [autoprefixer],
    },
  },
  build: {
    target: ['es2015'],
    outDir: 'dist',
    emptyOutDir: true,
    cssCodeSplit: true,
    sourcemap: false,
    lib: {
      formats: ['es'],
      entry: [
        resolve(__dirname, 'src/scripts/main.js'),
        resolve(__dirname, 'src/scripts/critical.js'),
        resolve(__dirname, 'src/scripts/blocks.js'),
        resolve(__dirname, 'src/scripts/admin.js'),
        resolve(__dirname, 'src/scripts/storybook.js'),
      ],
      fileName: '[name]',
    },
    watch:
      process.env.NODE_ENV != 'development'
        ? null
        : {
            exclude: 'node_modules/**',
            include: 'src/**',
          },
    rollupOptions: {
      output: {
        assetFileNames: (assetInfo) => {
          return assetInfo.name
        },
      },
    },
  },
  define: {
    'import.meta.env.LB_API_URL': JSON.stringify(process.env.LB_API_URL)
  },  
  plugins: [minifyBundles()],
})
