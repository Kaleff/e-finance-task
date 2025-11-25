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
      <!-- Name -->
      <CommonBaseInput
        :modelValue="props.form.name"
        @update:modelValue="updateField('name', $event)"
        type="text"
        label="Full Name"
        placeholder="John Doe"
        required
        :error="props.errors.name"
        @blur="emit('blur-name')"
      >
        <template #prefix>
          <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
        </template>
      </CommonBaseInput>

      <!-- Email -->
      <CommonBaseInput
        :modelValue="props.form.email"
        @update:modelValue="updateField('email', $event)"
        type="email"
        label="Email address"
        placeholder="john@example.com"
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
        label="Password"
        placeholder="••••••••"
        required
        :error="props.errors.password"
        hint="Must be at least 8 characters"
        @blur="emit('blur-password')"
      >
        <template #prefix>
          <svg class="h-5 w-5 text-[#1b1b18]/50 dark:text-[#EDEDEC]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </template>
      </CommonBaseInput>

      <!-- Password Confirmation -->
      <CommonBaseInput
        :modelValue="props.form.password_confirmation"
        @update:modelValue="updateField('password_confirmation', $event)"
        type="password"
        label="Confirm Password"
        placeholder="••••••••"
        required
        :error="props.errors.password_confirmation"
        @blur="emit('blur-password-confirmation')"
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
        :checked="props.form.acceptTerms"
        @change="updateField('acceptTerms', $event.target.checked)"
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
        :loading="props.loading"
        full-width
      >
        <template #icon>
          <svg class="h-5 w-5 text-white/80 group-hover:text-white" viewBox="0 0 20 20" fill="currentColor">
            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
          </svg>
        </template>
        {{ props.loading ? 'Creating account...' : 'Create account' }}
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

const emit = defineEmits(['update:form', 'submit', 'blur-name', 'blur-email', 'blur-password', 'blur-password-confirmation'])

const updateField = (field, value) => {
  emit('update:form', { ...props.form, [field]: value })
}

const handleSubmit = () => {
  emit('submit')
}
</script>
