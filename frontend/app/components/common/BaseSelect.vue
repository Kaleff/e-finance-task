<template>
  <div class="w-full">
    <label 
      v-if="label" 
      :for="selectId" 
      :class="labelClasses"
    >
      {{ label }}
      <span v-if="required" class="text-[#f53003] dark:text-[#FF4433]">*</span>
    </label>
    
    <div class="relative" :class="{ 'mt-2': label }">
      <select
        :id="selectId"
        :value="modelValue"
        :disabled="disabled"
        :required="required"
        :class="selectClasses"
        @change="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      >
        <option v-if="placeholder" value="" disabled>{{ placeholder }}</option>
        <slot />
      </select>
      
      <!-- Dropdown Arrow Icon -->
      <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <svg class="h-5 w-5 text-[#1b1b18]/60 dark:text-[#EDEDEC]/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
      </div>
    </div>
    
    <p v-if="error" class="mt-1 text-xs text-red-600 dark:text-red-400">
      {{ error }}
    </p>
    
    <p v-else-if="hint" class="mt-1 text-xs text-[#1b1b18]/60 dark:text-[#EDEDEC]/60">
      {{ hint }}
    </p>
  </div>
</template>

<script setup>
const props = defineProps({
  modelValue: {
    type: [String, Number, Boolean],
    default: ''
  },
  label: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  hint: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  required: {
    type: Boolean,
    default: false
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  }
})

defineEmits(['update:modelValue', 'blur', 'focus'])

const selectId = computed(() => `select-${Math.random().toString(36).substr(2, 9)}`)

const labelClasses = computed(() => {
  return 'block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]'
})

const selectClasses = computed(() => {
  const baseClasses = 'appearance-none relative block w-full border rounded-sm bg-white dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors pr-10'
  
  const sizeClasses = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-4 py-3 text-base'
  }
  
  const stateClasses = props.error
    ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
    : 'border-[#e3e3e0] dark:border-[#3E3E3A] focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-[#f53003] dark:focus:border-[#FF4433]'
  
  return [
    baseClasses,
    sizeClasses[props.size],
    stateClasses
  ].join(' ')
})
</script>
