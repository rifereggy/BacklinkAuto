<template>
  <header class="bg-dark-800 border-b border-dark-700">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
      <!-- Mobile menu button -->
      <button
        @click="$emit('toggle-sidebar')"
        class="lg:hidden p-2 rounded-md text-dark-400 hover:text-white hover:bg-dark-700"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>

      <!-- Search bar (hidden on mobile) -->
      <div class="hidden md:flex flex-1 max-w-lg ml-4">
        <div class="relative w-full">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <input
            type="text"
            placeholder="Search campaigns..."
            class="block w-full pl-10 pr-3 py-2 border border-dark-600 rounded-md leading-5 bg-dark-700 text-white placeholder-dark-400 focus:outline-none focus:placeholder-dark-500 focus:ring-1 focus:ring-primary-500 focus:border-primary-500"
          />
        </div>
      </div>

      <!-- Right side -->
      <div class="flex items-center space-x-4">
        <!-- Theme toggle -->
        <button 
          @click="toggleTheme" 
          class="p-2 text-dark-400 hover:text-white hover:bg-dark-700 rounded-md"
          :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
        >
          <svg v-if="isDark" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
          </svg>
        </button>

        <!-- Notifications -->
        <button class="p-2 text-dark-400 hover:text-white hover:bg-dark-700 rounded-md">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 006 3h12a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z" />
          </svg>
        </button>

        <!-- Profile dropdown -->
        <div class="relative">
          <button
            @click="showingUserDropdown = !showingUserDropdown"
            class="flex items-center space-x-3 p-2 rounded-md text-dark-400 hover:text-white hover:bg-dark-700"
          >
            <img
              :src="$page.props.auth.user.profile_photo_url"
              :alt="$page.props.auth.user.name"
              class="h-8 w-8 rounded-full"
            />
            <span class="hidden md:block text-sm font-medium">{{ $page.props.auth.user.name }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
          </button>

          <!-- Dropdown menu -->
          <div
            v-show="showingUserDropdown"
            class="absolute right-0 mt-2 w-48 bg-dark-800 rounded-md shadow-lg py-1 z-50 border border-dark-700"
          >
            <Link
              :href="route('profile.show')"
              class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-white"
            >
              Your Profile
            </Link>
            <Link
              :href="route('api-tokens.index')"
              class="block px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-white"
            >
              API Tokens
            </Link>
            <div class="border-t border-dark-700"></div>
            <form @submit.prevent="logout">
              <button
                type="submit"
                class="block w-full text-left px-4 py-2 text-sm text-dark-300 hover:bg-dark-700 hover:text-white"
              >
                Sign out
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import { useTheme } from '@/composables/useTheme'

const $page = usePage()
const showingUserDropdown = ref(false)

const { isDark, toggleTheme } = useTheme()

const logout = () => {
  useForm().post(route('logout'))
}

defineEmits(['toggle-sidebar'])
</script> 