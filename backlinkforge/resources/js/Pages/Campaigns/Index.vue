<template>
  <AppLayout title="Campaigns">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-white">Campaigns</h1>
            <p class="mt-2 text-dark-400">Manage your backlink campaigns</p>
          </div>
          <Link
            :href="route('campaigns.create')"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            New Campaign
          </Link>
        </div>

        <!-- Filters -->
        <Card class="mb-6">
          <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
              <Input
                v-model="search"
                placeholder="Search campaigns..."
                type="text"
              >
                <template #prefix>
                  <svg class="w-5 h-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </template>
              </Input>
            </div>
            <div class="flex gap-2">
              <select
                v-model="statusFilter"
                class="px-3 py-2 border border-dark-600 rounded-md bg-dark-700 text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
              >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="paused">Paused</option>
                <option value="completed">Completed</option>
                <option value="draft">Draft</option>
              </select>
              <Button variant="outline" @click="resetFilters">
                Reset
              </Button>
            </div>
          </div>
        </Card>

        <!-- Campaigns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <Card
            v-for="campaign in filteredCampaigns"
            :key="campaign.id"
            class="hover:shadow-lg transition-shadow duration-200 cursor-pointer"
            @click="viewCampaign(campaign.id)"
          >
            <div class="flex items-start justify-between mb-4">
              <div class="flex-1">
                <h3 class="text-lg font-semibold text-white mb-1">{{ campaign.name }}</h3>
                <p class="text-sm text-dark-400 mb-3">{{ campaign.description }}</p>
              </div>
              <Badge :variant="getStatusVariant(campaign.status)">
                {{ campaign.status }}
              </Badge>
            </div>

            <div class="space-y-3">
              <div class="flex items-center justify-between text-sm">
                <span class="text-dark-400">Links Created:</span>
                <span class="text-white font-medium">{{ campaign.links_created }}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-dark-400">Success Rate:</span>
                <span class="text-white font-medium">{{ campaign.success_rate }}%</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-dark-400">Last Updated:</span>
                <span class="text-white font-medium">{{ campaign.updated_at }}</span>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-dark-700 flex items-center justify-between">
              <div class="flex space-x-2">
                <Button
                  size="sm"
                  variant="ghost"
                  @click.stop="editCampaign(campaign.id)"
                >
                  Edit
                </Button>
                <Button
                  size="sm"
                  variant="ghost"
                  @click.stop="duplicateCampaign(campaign.id)"
                >
                  Duplicate
                </Button>
              </div>
              <Button
                size="sm"
                :variant="campaign.status === 'active' ? 'danger' : 'primary'"
                @click.stop="toggleCampaign(campaign.id)"
              >
                {{ campaign.status === 'active' ? 'Pause' : 'Start' }}
              </Button>
            </div>
          </Card>
        </div>

        <!-- Empty State -->
        <div v-if="filteredCampaigns.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-white">No campaigns found</h3>
          <p class="mt-1 text-sm text-dark-400">
            {{ search || statusFilter ? 'Try adjusting your filters.' : 'Get started by creating a new campaign.' }}
          </p>
          <div class="mt-6">
            <Link
              :href="route('campaigns.create')"
              class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              New Campaign
            </Link>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'
import Input from '@/Components/UI/Input.vue'

// Mock data for campaigns
const campaigns = ref([
  {
    id: 1,
    name: 'Tech Blog Outreach',
    description: 'Building backlinks from technology blogs and news sites',
    status: 'active',
    links_created: 45,
    success_rate: 78,
    updated_at: '2 hours ago'
  },
  {
    id: 2,
    name: 'Local Business Links',
    description: 'Local directory and business listing submissions',
    status: 'paused',
    links_created: 23,
    success_rate: 65,
    updated_at: '1 day ago'
  },
  {
    id: 3,
    name: 'E-commerce Backlinks',
    description: 'Product review and affiliate link building',
    status: 'completed',
    links_created: 89,
    success_rate: 82,
    updated_at: '3 days ago'
  },
  {
    id: 4,
    name: 'Guest Post Campaign',
    description: 'High-authority guest posting strategy',
    status: 'draft',
    links_created: 0,
    success_rate: 0,
    updated_at: '1 week ago'
  }
])

const search = ref('')
const statusFilter = ref('')

const filteredCampaigns = computed(() => {
  return campaigns.value.filter(campaign => {
    const matchesSearch = campaign.name.toLowerCase().includes(search.value.toLowerCase()) ||
                         campaign.description.toLowerCase().includes(search.value.toLowerCase())
    const matchesStatus = !statusFilter.value || campaign.status === statusFilter.value
    return matchesSearch && matchesStatus
  })
})

const getStatusVariant = (status) => {
  const variants = {
    active: 'success',
    paused: 'warning',
    completed: 'info',
    draft: 'default'
  }
  return variants[status] || 'default'
}

const resetFilters = () => {
  search.value = ''
  statusFilter.value = ''
}

const viewCampaign = (id) => {
  // Navigate to campaign details
  console.log('View campaign:', id)
}

const editCampaign = (id) => {
  // Navigate to edit campaign
  console.log('Edit campaign:', id)
}

const duplicateCampaign = (id) => {
  // Duplicate campaign
  console.log('Duplicate campaign:', id)
}

const toggleCampaign = (id) => {
  // Toggle campaign status
  const campaign = campaigns.value.find(c => c.id === id)
  if (campaign) {
    campaign.status = campaign.status === 'active' ? 'paused' : 'active'
  }
}
</script> 