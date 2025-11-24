// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  srcDir: 'app/',

  modules: [
    '@nuxt/eslint',
    '@nuxt/hints',
    '@nuxt/image',
    '@nuxt/scripts',
    '@nuxt/ui',
    '@pinia/nuxt',
    'pinia-plugin-persistedstate/nuxt'
  ],

  pinia: {
    storesDirs: ['stores', 'composables']
  },

  runtimeConfig: {
    public: {
      apiBase: 'http://localhost/api'
    }
  },

  css: ['~/assets/css/main.css'],

  app: {
    head: {
      title: 'Task Management',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'Task Management Application' }
      ]
    }
  },

  vite: {
    server: {
      watch: {
        usePolling: true,
        interval: 1000,
      }
    }
  }
})