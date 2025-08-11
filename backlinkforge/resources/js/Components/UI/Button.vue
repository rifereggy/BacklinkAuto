<template>
  <button
    :class="buttonClasses"
    :disabled="disabled"
    :type="type"
    @click="$emit('click', $event)"
  >
    <slot />
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'secondary', 'outline', 'ghost', 'danger'].includes(value)
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
  type: {
    type: String,
    default: 'button'
  },
  fullWidth: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['click'])

const buttonClasses = computed(() => {
  const baseClasses = [
    'inline-flex items-center justify-center font-medium rounded-md transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-dark-900',
    'disabled:opacity-50 disabled:cursor-not-allowed'
  ]

  const sizeClasses = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base'
  }

  const variantClasses = {
    primary: 'bg-primary-700 text-white hover:bg-primary-600 focus:ring-primary-500',
    secondary: 'bg-dark-700 text-white hover:bg-dark-600 focus:ring-dark-500',
    outline: 'border border-dark-600 bg-transparent text-dark-200 hover:bg-dark-800 focus:ring-dark-500',
    ghost: 'bg-transparent text-dark-200 hover:bg-dark-800 focus:ring-dark-500',
    danger: 'bg-error-600 text-white hover:bg-error-500 focus:ring-error-500'
  }

  const widthClass = props.fullWidth ? 'w-full' : ''

  return [
    ...baseClasses,
    sizeClasses[props.size],
    variantClasses[props.variant],
    widthClass
  ].join(' ')
})
</script> 