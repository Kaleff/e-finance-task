<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    :class="buttonClasses"
    @click="$emit('click', $event)"
  >
    <span v-if="loading" class="absolute left-0 inset-y-0 flex items-center pl-3">
      <CommonBaseSpinner color="text-white" />
    </span>
    <span v-else-if="$slots.icon" class="absolute left-0 inset-y-0 flex items-center pl-3">
      <slot name="icon" />
    </span>
    <slot />
  </button>
</template>

<script setup>
const props = defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'outline', 'ghost'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  },
  fullWidth: {
    type: Boolean,
    default: false
  }
})

defineEmits(['click'])

const buttonClasses = computed(() => {
  const baseClasses = 'cursor-pointer relative inline-flex items-center justify-center font-medium rounded-sm transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed'
  
  const sizeClasses = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base'
  }
  
  const variantClasses = {
    primary: 'text-white bg-[#f53003] dark:bg-[#FF4433] hover:bg-[#d42a02] dark:hover:bg-[#ff5544] focus:ring-[#f53003] dark:focus:ring-[#FF4433]',
    secondary: 'text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] hover:border-[#f53003] dark:hover:border-[#FF4433] focus:ring-[#f53003] dark:focus:ring-[#FF4433]',
    outline: 'text-[#f53003] dark:text-[#FF4433] border border-[#f53003] dark:border-[#FF4433] hover:bg-[#f53003]/10 dark:hover:bg-[#FF4433]/10 focus:ring-[#f53003] dark:focus:ring-[#FF4433]',
    ghost: 'text-[#1b1b18] dark:text-[#EDEDEC] hover:bg-[#e3e3e0]/50 dark:hover:bg-[#3E3E3A]/50 focus:ring-[#e3e3e0] dark:focus:ring-[#3E3E3A]'
  }
  
  const widthClass = props.fullWidth ? 'w-full' : ''
  
  return [
    baseClasses,
    sizeClasses[props.size],
    variantClasses[props.variant],
    widthClass
  ].join(' ')
})
</script>
