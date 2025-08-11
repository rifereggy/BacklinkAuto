<script setup>
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Badge from '@/Components/UI/Badge.vue'
import { Link } from '@inertiajs/vue3'

// Mock data for recent campaigns
const recentCampaigns = [
  {
    id: 1,
    name: 'Tech Blog Outreach',
    status: 'active',
    created_at: '2 hours ago'
  },
  {
    id: 2,
    name: 'Local Business Links',
    status: 'paused',
    created_at: '1 day ago'
  },
  {
    id: 3,
    name: 'E-commerce Backlinks',
    status: 'active',
    created_at: '3 days ago'
  }
]
</script>

<template>
  <AppLayout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
          <Card>
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-primary-600 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-dark-400">Total Campaigns</p>
                <p class="text-2xl font-semibold text-white">12</p>
              </div>
            </div>
          </Card>

          <Card>
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-success-600 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-dark-400">Active Campaigns</p>
                <p class="text-2xl font-semibold text-white">8</p>
              </div>
            </div>
          </Card>

          <Card>
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-warning-600 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-dark-400">Links Created</p>
                <p class="text-2xl font-semibold text-white">1,247</p>
              </div>
            </div>
          </Card>

          <Card>
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-info-600 rounded-md flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                </div>
              </div>
              <div class="ml-4">
                <p class="text-sm font-medium text-dark-400">Templates</p>
                <p class="text-2xl font-semibold text-white">24</p>
              </div>
            </div>
          </Card>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
          <Card>
            <template #header>
              <h3 class="text-lg font-medium text-white">Recent Campaigns</h3>
            </template>
            <div class="space-y-4">
              <div v-for="campaign in recentCampaigns" :key="campaign.id" class="flex items-center justify-between">
                <div class="flex items-center">
                  <div class="w-2 h-2 rounded-full" :class="campaign.status === 'active' ? 'bg-success-500' : 'bg-dark-500'"></div>
                  <div class="ml-3">
                    <p class="text-sm font-medium text-white">{{ campaign.name }}</p>
                    <p class="text-sm text-dark-400">{{ campaign.created_at }}</p>
                  </div>
                </div>
                <Badge :variant="campaign.status === 'active' ? 'success' : 'default'">
                  {{ campaign.status }}
                </Badge>
              </div>
            </div>
          </Card>

          <Card>
            <template #header>
              <h3 class="text-lg font-medium text-white">Quick Actions</h3>
            </template>
            <div class="space-y-3">
              <Link
                :href="route('campaigns.create')"
                class="flex items-center p-3 rounded-lg bg-dark-700 hover:bg-dark-600 transition-colors duration-200"
              >
                <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span class="text-white">Create New Campaign</span>
              </Link>
              
              <Link
                :href="route('templates.index')"
                class="flex items-center p-3 rounded-lg bg-dark-700 hover:bg-dark-600 transition-colors duration-200"
              >
                <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
                <span class="text-white">Browse Templates</span>
              </Link>
              
              <Link
                :href="route('analytics.index')"
                class="flex items-center p-3 rounded-lg bg-dark-700 hover:bg-dark-600 transition-colors duration-200"
              >
                <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span class="text-white">View Analytics</span>
              </Link>
            </div>
          </Card>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
