<template>
  <div class="w-full">
    <label 
      v-if="label" 
      :for="inputId" 
      :class="labelClasses"
    >
      {{ label }}
      <span v-if="required" class="text-[#f53003] dark:text-[#FF4433]">*</span>
    </label>
    
    <div class="relative" :class="{ 'mt-1': label }">
      <div v-if="$slots.prefix" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <slot name="prefix" />
      </div>
      
      <input
        :id="inputId"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :class="inputClasses"
        @input="$emit('update:modelValue', $event.target.value)"
        @blur="$emit('blur', $event)"
        @focus="$emit('focus', $event)"
      >
      
      <div v-if="$slots.suffix" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <slot name="suffix" />
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
    type: [String, Number],
    default: ''
  },
  type: {
    type: String,
    default: 'text'
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
  },
  rounded: {
    type: String,
    default: 'sm',
    validator: (value) => ['none', 'sm', 'md', 'lg', 'full'].includes(value)
  }
})

defineEmits(['update:modelValue', 'blur', 'focus'])

const inputId = computed(() => `input-${Math.random().toString(36).substr(2, 9)}`)

const labelClasses = computed(() => {
  return 'block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]'
})

const inputClasses = computed(() => {
  const baseClasses = 'appearance-none relative block w-full border placeholder-[#1b1b18]/50 dark:placeholder-[#EDEDEC]/50 text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#0a0a0a] focus:outline-none focus:ring-1 disabled:opacity-50 disabled:cursor-not-allowed transition-colors'
  
  const sizeClasses = {
    sm: 'px-2 py-1 text-xs',
    md: 'px-3 py-2 text-sm',
    lg: 'px-4 py-3 text-base'
  }
  
  const roundedClasses = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    full: 'rounded-full'
  }
  
  const stateClasses = props.error
    ? 'border-red-500 focus:border-red-500 focus:ring-red-500'
    : 'border-[#e3e3e0] dark:border-[#3E3E3A] focus:ring-[#f53003] dark:focus:ring-[#FF4433] focus:border-[#f53003] dark:focus:border-[#FF4433]'
  
  const paddingClasses = []
  if (props.$slots?.prefix) paddingClasses.push('pl-10')
  if (props.$slots?.suffix) paddingClasses.push('pr-10')
  
  return [
    baseClasses,
    sizeClasses[props.size],
    roundedClasses[props.rounded],
    stateClasses,
    ...paddingClasses
  ].join(' ')
})
</script>
