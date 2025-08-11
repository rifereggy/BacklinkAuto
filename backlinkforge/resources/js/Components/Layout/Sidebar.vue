<template>
  <div class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-800 border-r border-dark-700 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0">
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-6 border-b border-dark-700">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <h1 class="text-xl font-bold text-white">BacklinkForge</h1>
        </div>
      </div>
      <button
        @click="$emit('close')"
        class="lg:hidden p-2 rounded-md text-dark-400 hover:text-white hover:bg-dark-700"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
      <div class="space-y-1">
        <template v-for="item in navigationItems" :key="item.name">
          <Link
            :href="item.href"
            :class="[
              'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
              $page.url.startsWith(item.href)
                ? 'bg-primary-700 text-white'
                : 'text-dark-300 hover:bg-dark-700 hover:text-white'
            ]"
          >
            <component
              :is="item.icon"
              :class="[
                'mr-3 flex-shrink-0 h-5 w-5',
                $page.url.startsWith(item.href)
                  ? 'text-white'
                  : 'text-dark-400 group-hover:text-white'
              ]"
            />
            {{ item.name }}
          </Link>
        </template>
      </div>

      <!-- Divider -->
      <div class="mt-8 pt-6 border-t border-dark-700">
        <h3 class="px-3 text-xs font-semibold text-dark-400 uppercase tracking-wider">
          Tools
        </h3>
        <div class="mt-3 space-y-1">
          <template v-for="item in toolItems" :key="item.name">
            <Link
              :href="item.href"
              :class="[
                'group flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors duration-200',
                $page.url.startsWith(item.href)
                  ? 'bg-primary-700 text-white'
                  : 'text-dark-300 hover:bg-dark-700 hover:text-white'
              ]"
            >
              <component
                :is="item.icon"
                :class="[
                  'mr-3 flex-shrink-0 h-5 w-5',
                  $page.url.startsWith(item.href)
                    ? 'text-white'
                    : 'text-dark-400 group-hover:text-white'
                ]"
              />
              {{ item.name }}
            </Link>
          </template>
        </div>
      </div>
    </nav>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

// Icons
const HomeIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" /></svg>`
}

const CampaignIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>`
}

const TemplateIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" /></svg>`
}

const SettingsIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`
}

const AnalyticsIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>`
}

const navigationItems = [
  { name: 'Dashboard', href: '/dashboard', icon: HomeIcon },
  { name: 'Campaigns', href: '/campaigns', icon: CampaignIcon },
  { name: 'Templates', href: '/templates', icon: TemplateIcon },
]

const toolItems = [
  { name: 'Analytics', href: '/analytics', icon: AnalyticsIcon },
  { name: 'Settings', href: '/settings', icon: SettingsIcon },
]

const $page = usePage()

defineEmits(['close'])
</script> 