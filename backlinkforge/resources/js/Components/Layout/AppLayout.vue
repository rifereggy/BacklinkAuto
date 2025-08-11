<template>
  <div class="min-h-screen bg-dark-900">
    <!-- Sidebar -->
    <Sidebar :class="{ 'translate-x-0': showingNavigationDropdown, '-translate-x-full': !showingNavigationDropdown }" />
    
    <!-- Main content -->
    <div class="lg:pl-64">
      <!-- Top navigation -->
      <Header @toggle-sidebar="showingNavigationDropdown = !showingNavigationDropdown" />
      
      <!-- Page content -->
      <main class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import Sidebar from './Sidebar.vue'
import Header from './Header.vue'

defineProps({
  title: {
    type: String,
    default: 'BacklinkForge'
  }
})

const showingNavigationDropdown = ref(false)
</script> 