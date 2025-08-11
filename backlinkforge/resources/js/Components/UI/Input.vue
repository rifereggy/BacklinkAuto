<template>
  <div class="w-full">
    <label v-if="label" :for="id" class="block text-sm font-medium text-dark-200 mb-2">
      {{ label }}
      <span v-if="required" class="text-error-500">*</span>
    </label>
    <div class="relative">
      <input
        :id="id"
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :class="inputClasses"
        @input="$emit('update:modelValue', $event.target.value)"
        @focus="$emit('focus', $event)"
        @blur="$emit('blur', $event)"
      />
      <div v-if="$slots.prefix" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <slot name="prefix" />
      </div>
      <div v-if="$slots.suffix" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
        <slot name="suffix" />
      </div>
    </div>
    <p v-if="error" class="mt-1 text-sm text-error-500">{{ error }}</p>
    <p v-else-if="help" class="mt-1 text-sm text-dark-400">{{ help }}</p>
  </div>
</template>

<script setup>
import { computed } from 'vue'

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
  id: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  },
  help: {
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

const emit = defineEmits(['update:modelValue', 'focus', 'blur'])

const inputClasses = computed(() => {
  const baseClasses = [
    'block w-full border border-dark-600 rounded-md shadow-sm',
    'bg-dark-800 text-dark-100 placeholder-dark-500',
    'focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    'disabled:opacity-50 disabled:cursor-not-allowed',
    'transition-colors duration-200'
  ]

  const sizeClasses = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-3 py-2 text-sm',
    lg: 'px-4 py-3 text-base'
  }

  const errorClasses = props.error ? 'border-error-500 focus:ring-error-500 focus:border-error-500' : ''

  const prefixSuffixClasses = (props.$slots.prefix ? 'pl-10' : '') + (props.$slots.suffix ? 'pr-10' : '')

  return [
    ...baseClasses,
    sizeClasses[props.size],
    errorClasses,
    prefixSuffixClasses
  ].join(' ')
})
</script> 