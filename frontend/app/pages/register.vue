<template>
  <div class="min-h-screen flex items-center justify-center bg-[#FDFDFC] dark:bg-[#0a0a0a] py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h2 class="mt-6 text-center text-3xl font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
          Create your account
        </h2>
        <p class="mt-2 text-center text-sm text-[#1b1b18]/70 dark:text-[#EDEDEC]/70">
          Or
          <NuxtLink to="/login" class="font-medium text-[#f53003] dark:text-[#FF4433] hover:text-[#d42a02] dark:hover:text-[#ff5544]">
            sign in to existing account
          </NuxtLink>
        </p>
      </div>

      <form class="mt-8 space-y-6" @submit.prevent="handleRegister">
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
          <!-- Name -->
          <CommonBaseInput
            v-model="formData.name"
            type="text"
            label="Full Name"
            placeholder="John Doe"
            required
            :error="errors.name"
            @blur="validateName"
          >
            <template #prefix>
              <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </template>
          </CommonBaseInput>

          <!-- Email -->
          <CommonBaseInput
            v-model="formData.email"
            type="email"
            label="Email address"
            placeholder="john@example.com"
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
            label="Password"
            placeholder="••••••••"
            required
            :error="errors.password"
            hint="Must be at least 8 characters"
            @blur="validatePassword"
          >
            <template #prefix>
              <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </template>
          </CommonBaseInput>

          <!-- Password Confirmation -->
          <CommonBaseInput
            v-model="formData.password_confirmation"
            type="password"
            label="Confirm Password"
            placeholder="••••••••"
            required
            :error="errors.password_confirmation"
            @blur="validatePasswordConfirmation"
          >
            <template #prefix>
              <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </template>
          </CommonBaseInput>
        </div>

        <div class="flex items-center">
          <input
            id="terms"
            v-model="formData.acceptTerms"
            type="checkbox"
            required
            class="h-4 w-4 text-[#f53003] dark:text-[#FF4433] focus:ring-[#f53003] dark:focus:ring-[#FF4433] border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm"
          />
          <label for="terms" class="ml-2 block text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
            I agree to the
            <a href="#" class="text-[#f53003] dark:text-[#FF4433] hover:text-[#d42a02] dark:hover:text-[#ff5544]">Terms and Conditions</a>
          </label>
        </div>

        <div>
          <CommonBaseButton
            type="submit"
            :loading="loading"
            full-width
          >
            <template #icon>
              <svg class="h-5 w-5 text-white/80 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
                <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
              </svg>
            </template>
            {{ loading ? 'Creating account...' : 'Create account' }}
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

const { register } = useAuth()
const router = useRouter()
const uiStore = useUiStore()

const formData = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  acceptTerms: false
})

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const loading = ref(false)
const errorMessage = ref('')

const validateName = () => {
  errors.name = ''
  if (!formData.name) {
    errors.name = 'Name is required'
  } else if (formData.name.length < 2) {
    errors.name = 'Name must be at least 2 characters'
  }
}

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
  } else if (formData.password.length < 8) {
    errors.password = 'Password must be at least 8 characters'
  } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(formData.password)) {
    errors.password = 'Password must contain uppercase, lowercase, and numbers'
  }
}

const validatePasswordConfirmation = () => {
  errors.password_confirmation = ''
  if (!formData.password_confirmation) {
    errors.password_confirmation = 'Please confirm your password'
  } else if (formData.password !== formData.password_confirmation) {
    errors.password_confirmation = 'Passwords do not match'
  }
}

const validateForm = () => {
  validateName()
  validateEmail()
  validatePassword()
  validatePasswordConfirmation()
  
  return !errors.name && !errors.email && !errors.password && !errors.password_confirmation && formData.acceptTerms
}

const handleRegister = async () => {
  errorMessage.value = ''
  
  if (!validateForm()) {
    if (!formData.acceptTerms) {
      errorMessage.value = 'You must accept the terms and conditions'
    }
    return
  }

  loading.value = true

  try {
    await register({
      name: formData.name,
      email: formData.email,
      password: formData.password,
      password_confirmation: formData.password_confirmation
    })

    uiStore.addNotification({
      type: 'success',
      message: 'Account created successfully! Welcome!'
    })

    // Redirect to dashboard
    router.push('/')
  } catch (error) {
    console.error('Registration error:', error)
    
    // Handle validation errors from API
    if (error.data?.errors) {
      const apiErrors = error.data.errors
      Object.keys(apiErrors).forEach(key => {
        if (errors.hasOwnProperty(key)) {
          errors[key] = apiErrors[key][0]
        }
      })
      errorMessage.value = 'Please fix the errors below'
    } else {
      errorMessage.value = error.data?.message || error.message || 'Registration failed. Please try again.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Additional styles if needed */
</style>
