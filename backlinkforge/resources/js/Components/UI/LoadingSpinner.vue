<template>
  <div :class="containerClasses">
    <div :class="spinnerClasses" role="status" aria-label="Loading">
      <svg
        class="animate-spin"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle
          class="opacity-25"
          cx="12"
          cy="12"
          r="10"
          stroke="currentColor"
          stroke-width="4"
        ></circle>
        <path
          class="opacity-75"
          fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        ></path>
      </svg>
      <span v-if="text" class="sr-only">{{ text }}</span>
    </div>
    <p v-if="text && !srOnly" class="mt-2 text-sm text-dark-400">{{ text }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg', 'xl'].includes(value)
  },
  text: {
    type: String,
    default: ''
  },
  srOnly: {
    type: Boolean,
    default: false
  },
  color: {
    type: String,
    default: 'primary',
    validator: (value) => ['primary', 'white', 'dark'].includes(value)
  }
})

const containerClasses = computed(() => {
  return 'flex flex-col items-center justify-center'
})

const spinnerClasses = computed(() => {
  const sizeClasses = {
    sm: 'w-4 h-4',
    md: 'w-6 h-6',
    lg: 'w-8 h-8',
    xl: 'w-12 h-12'
  }

  const colorClasses = {
    primary: 'text-primary-500',
    white: 'text-white',
    dark: 'text-dark-400'
  }

  return [
    sizeClasses[props.size],
    colorClasses[props.color]
  ].join(' ')
})
</script> 