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

      <FormLogin
        v-model:form="formData"
        :errors="errors"
        :loading="loading"
        :error-message="errorMessage"
        @submit="handleLogin"
        @blur-email="validateEmail"
        @blur-password="validatePassword"
      />
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

const formData = ref({
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
  if (!formData.value.email) {
    errors.email = 'Email is required'
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
    errors.email = 'Please enter a valid email address'
  }
}

const validatePassword = () => {
  errors.password = ''
  if (!formData.value.password) {
    errors.password = 'Password is required'
  } else if (formData.value.password.length < 6) {
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
      email: formData.value.email,
      password: formData.value.password,
      remember: formData.value.remember
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
