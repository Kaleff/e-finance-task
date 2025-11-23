<template>
  <div class="min-h-screen flex items-center justify-center bg-[#FDFDFC] dark:bg-[#0a0a0a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
          Sign in to your account
        </h2>
        <p class="mt-2 text-center text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
          Or
          <NuxtLink to="/register" class="font-medium text-[#f53003] dark:text-[#FF4433] hover:text-[#d42a02] dark:hover:text-[#ff5544]">
            create a new account
          </NuxtLink>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <!-- Error Message -->
        <div v-if="errorMessage" class="rounded-sm bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ errorMessage }}</p>
            </div>
          </div>
        </div>

        <div class="space-y-4">
          <!-- Email -->
          <CommonBaseInput
            v-model="formData.email"
            type="email"
            placeholder="Email address"
            required
            :error="errors.email"
            @blur="validateEmail"
          >
            <template #prefix>
              <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
            </template>
          </CommonBaseInput>

          <!-- Password -->
          <CommonBaseInput
            v-model="formData.password"
            type="password"
            placeholder="Password"
            required
            :error="errors.password"
            @blur="validatePassword"
          >
            <template #prefix>
              <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </template>
          </CommonBaseInput>
        </div>

        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              v-model="formData.remember"
              type="checkbox"
              class="h-4 w-4 text-[#f53003] dark:text-[#FF4433] focus:ring-[#f53003] dark:focus:ring-[#FF4433] border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm"
            />
            <label for="remember-me" class="ml-2 block text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
              Remember me
            </label>
          </div>

          <div class="text-sm">
            <a href="#" class="font-medium text-[#f53003] dark:text-[#FF4433] hover:text-[#d42a02] dark:hover:text-[#ff5544]">
              Forgot your password?
            </a>
          </div>
        </div>

        <div>
          <CommonBaseButton
            type="submit"
            :loading="loading"
            full-width
          >
            <template #icon>
              <svg class="h-5 w-5 text-white/80 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </template>
            {{ loading ? 'Signing in...' : 'Sign in' }}
          </CommonBaseButton>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>

definePageMeta({
  layout: false,
  middleware: 'guest'
})

const { login } = useAuth()
const router = useRouter()
const uiStore = useUiStore()

const formData = reactive({
  email: '',
  password: '',
  remember: false
})

const errors = reactive({
  email: '',
  password: ''
})

const loading = ref(false)
const errorMessage = ref('')

const validateEmail = () => {
  errors.email = ''
  if (!formData.email) {
    errors.email = 'Email is required'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.email)) {
    errors.email = 'Please enter a valid email address'
  }
}

const validatePassword = () => {
  errors.password = ''
  if (!formData.password) {
    errors.password = 'Password is required'
  } else if (formData.password.length < 6) {
    errors.password = 'Password must be at least 6 characters'
  }
}

const validateForm = () => {
  validateEmail()
  validatePassword()
  return !errors.email && !errors.password
}

const handleLogin = async () => {
  errorMessage.value = ''
  
  if (!validateForm()) {
    return
  }

  loading.value = true

  try {
    await login({
      email: formData.email,
      password: formData.password,
      remember: formData.remember
    })

    uiStore.addNotification({
      type: 'success',
      message: 'Successfully logged in!'
    })

    // Redirect to dashboard
    router.push('/')
  } catch (error) {
    console.error('Login error:', error)
    errorMessage.value = error.data?.message || error.message || 'Invalid email or password. Please try again.'
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.sr-only {
  position: absolute;
  width: 1px;
  height: 1px;
  padding: 0;
  margin: -1px;
  overflow: hidden;
  clip: rect(0, 0, 0, 0);
  white-space: nowrap;
  border-width: 0;
}
</style>
