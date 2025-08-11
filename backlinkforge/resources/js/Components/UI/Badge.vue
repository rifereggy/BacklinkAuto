<template>
  <span :class="badgeClasses">
    <slot />
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'primary', 'success', 'warning', 'error', 'info'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value)
  },
  rounded: {
    type: Boolean,
    default: false
  }
})

const badgeClasses = computed(() => {
  const baseClasses = [
    'inline-flex items-center font-medium'
  ]

  const sizeClasses = {
    sm: 'px-2 py-0.5 text-xs',
    md: 'px-2.5 py-0.5 text-sm',
    lg: 'px-3 py-1 text-sm'
  }

  const variantClasses = {
    default: 'bg-dark-700 text-dark-200',
    primary: 'bg-primary-700 text-white',
    success: 'bg-success-700 text-white',
    warning: 'bg-warning-700 text-white',
    error: 'bg-error-700 text-white',
    info: 'bg-info-700 text-white'
  }

  const roundedClasses = props.rounded ? 'rounded-full' : 'rounded-md'

  return [
    ...baseClasses,
    sizeClasses[props.size],
    variantClasses[props.variant],
    roundedClasses
  ].join(' ')
})
</script> 