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

      <FormRegister
        v-model:form="formData"
        :errors="errors"
        :loading="loading"
        :error-message="errorMessage"
        @submit="handleRegister"
        @blur-name="validateName"
        @blur-email="validateEmail"
        @blur-password="validatePassword"
        @blur-password-confirmation="validatePasswordConfirmation"
      />
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

const formData = ref({
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
  if (!formData.value.name) {
    errors.name = 'Name is required'
  } else if (formData.value.name.length < 2) {
    errors.name = 'Name must be at least 2 characters'
  }
}

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
  } else if (formData.value.password.length < 8) {
    errors.password = 'Password must be at least 8 characters'
  } else if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(formData.value.password)) {
    errors.password = 'Password must contain uppercase, lowercase, and numbers'
  }
}

const validatePasswordConfirmation = () => {
  errors.password_confirmation = ''
  if (!formData.value.password_confirmation) {
    errors.password_confirmation = 'Please confirm your password'
  } else if (formData.value.password !== formData.value.password_confirmation) {
    errors.password_confirmation = 'Passwords do not match'
  }
}

const validateForm = () => {
  validateName()
  validateEmail()
  validatePassword()
  validatePasswordConfirmation()
  
  return !errors.name && !errors.email && !errors.password && !errors.password_confirmation && formData.value.acceptTerms
}

const handleRegister = async () => {
  errorMessage.value = ''
  
  if (!validateForm()) {
    if (!formData.value.acceptTerms) {
      errorMessage.value = 'You must accept the terms and conditions'
    }
    return
  }

  loading.value = true

  try {
    await register({
      name: formData.value.name,
      email: formData.value.email,
      password: formData.value.password,
      password_confirmation: formData.value.password_confirmation
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
        if (Object.prototype.hasOwnProperty.call(errors, key)) {
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
