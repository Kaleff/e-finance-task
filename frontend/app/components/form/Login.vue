<template>
  <form class="mt-8 space-y-6" @submit.prevent="handleSubmit">
    <!-- Error Message -->
    <div v-if="props.errorMessage" class="rounded-sm bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-600 dark:text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ props.errorMessage }}</p>
        </div>
      </div>
    </div>

    <div class="space-y-4">
      <!-- Email -->
      <CommonBaseInput
        :modelValue="props.form.email"
        @update:modelValue="updateField('email', $event)"
        type="email"
        placeholder="Email address"
        required
        :error="props.errors.email"
        @blur="emit('blur-email')"
      >
        <template #prefix>
          <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
          </svg>
        </template>
      </CommonBaseInput>

      <!-- Password -->
      <CommonBaseInput
        :modelValue="props.form.password"
        @update:modelValue="updateField('password', $event)"
        type="password"
        placeholder="Password"
        required
        :error="props.errors.password"
        @blur="emit('blur-password')"
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
          :checked="props.form.remember"
          @change="updateField('remember', $event.target.checked)"
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
        :loading="props.loading"
        full-width
      >
        <template #icon>
          <svg class="h-5 w-5 text-white/80 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
          </svg>
        </template>
        {{ props.loading ? 'Signing in...' : 'Sign in' }}
      </CommonBaseButton>
    </div>
  </form>
</template>

<script setup>
const props = defineProps({
  form: {
    type: Object,
    required: true
  },
  errors: {
    type: Object,
    required: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  errorMessage: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:form', 'submit', 'blur-email', 'blur-password'])

const updateField = (field, value) => {
  emit('update:form', { ...props.form, [field]: value })
}

const handleSubmit = () => {
  emit('submit')
}
</script>
