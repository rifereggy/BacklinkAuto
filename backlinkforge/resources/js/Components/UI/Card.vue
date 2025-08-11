<template>
  <div :class="cardClasses">
    <div v-if="$slots.header" class="px-6 py-4 border-b border-dark-700">
      <slot name="header" />
    </div>
    <div class="px-6 py-4">
      <slot />
    </div>
    <div v-if="$slots.footer" class="px-6 py-4 border-t border-dark-700 bg-dark-800/50">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'elevated', 'outlined'].includes(value)
  },
  padding: {
    type: String,
    default: 'default',
    validator: (value) => ['none', 'sm', 'default', 'lg'].includes(value)
  }
})

const cardClasses = computed(() => {
  const baseClasses = [
    'bg-dark-800 border border-dark-700 rounded-lg shadow-md'
  ]

  const variantClasses = {
    default: 'bg-dark-800',
    elevated: 'bg-dark-800 shadow-lg',
    outlined: 'bg-transparent border-2 border-dark-600'
  }

  const paddingClasses = {
    none: '',
    sm: 'p-4',
    default: 'p-6',
    lg: 'p-8'
  }

  return [
    ...baseClasses,
    variantClasses[props.variant],
    paddingClasses[props.padding]
  ].join(' ')
})
</script> 