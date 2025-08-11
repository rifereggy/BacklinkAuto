<template>
    <AppLayout :title="campaign.name">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-primary">{{ campaign.name }}</h2>
                    <p class="text-muted">{{ campaign.description }}</p>
                </div>
                <div class="flex gap-3">
                    <Button
                        variant="outline"
                        @click="$inertia.visit(route('campaigns.edit', campaign.id))"
                    >
                        Edit Campaign
                    </Button>
                    <Button
                        variant="outline"
                        @click="$inertia.visit(route('campaigns.index'))"
                    >
                        Back to Campaigns
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Campaign Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ campaign.links_count || 0 }}</div>
                        <div class="text-sm text-muted">Links Created</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">{{ campaign.active_links || 0 }}</div>
                        <div class="text-sm text-muted">Active Links</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-warning">{{ campaign.pending_links || 0 }}</div>
                        <div class="text-sm text-muted">Pending Links</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-info">{{ campaign.success_rate || 0 }}%</div>
                        <div class="text-sm text-muted">Success Rate</div>
                    </div>
                </Card>
            </div>

            <!-- Campaign Details -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <Card>
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Campaign Information</h3>
                        </template>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Status:</span>
                                <Badge :variant="getStatusVariant(campaign.status)">
                                    {{ campaign.status }}
                                </Badge>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Created:</span>
                                <span>{{ formatDate(campaign.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Last Updated:</span>
                                <span>{{ formatDate(campaign.updated_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Target URLs:</span>
                                <span>{{ campaign.target_urls?.length || 0 }}</span>
                            </div>
                        </div>
                    </Card>

                    <!-- Target URLs -->
                    <Card class="mt-6">
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Target URLs</h3>
                        </template>

                        <div class="space-y-2">
                            <div
                                v-for="(url, index) in campaign.target_urls"
                                :key="index"
                                class="flex items-center justify-between p-3 bg-surface rounded-md"
                            >
                                <span class="text-sm text-secondary break-all">{{ url }}</span>
                                <Button variant="ghost" size="sm">
                                    <span class="sr-only">Copy URL</span>
                                    📋
                                </Button>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Recent Activity -->
                <div>
                    <Card>
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Recent Activity</h3>
                        </template>

                        <div class="space-y-4">
                            <div v-if="campaign.links?.length" class="space-y-3">
                                <div
                                    v-for="link in campaign.links.slice(0, 5)"
                                    :key="link.id"
                                    class="flex items-center gap-3 p-3 bg-surface rounded-md"
                                >
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-primary">
                                            {{ link.platform }}
                                        </div>
                                        <div class="text-xs text-muted">
                                            {{ formatDate(link.created_at) }}
                                        </div>
                                    </div>
                                    <Badge :variant="getLinkStatusVariant(link.status)" size="sm">
                                        {{ link.status }}
                                    </Badge>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <div class="text-muted">No activity yet</div>
                                <div class="text-sm text-muted mt-1">
                                    Start your campaign to see activity here
                                </div>
                            </div>
                        </div>
                    </Card>

                    <!-- Quick Actions -->
                    <Card class="mt-6">
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Quick Actions</h3>
                        </template>

                        <div class="space-y-3">
                            <Button
                                variant="outline"
                                class="w-full justify-start"
                                @click="startCampaign"
                                :disabled="campaign.status === 'active'"
                            >
                                ▶️ Start Campaign
                            </Button>
                            <Button
                                variant="outline"
                                class="w-full justify-start"
                                @click="pauseCampaign"
                                :disabled="campaign.status !== 'active'"
                            >
                                ⏸️ Pause Campaign
                            </Button>
                            <Button
                                variant="outline"
                                class="w-full justify-start"
                                @click="exportData"
                            >
                                📊 Export Data
                            </Button>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { computed } from 'vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'

const props = defineProps({
    campaign: {
        type: Object,
        required: true,
    },
})

const getStatusVariant = (status) => {
    const variants = {
        draft: 'default',
        active: 'success',
        paused: 'warning',
        completed: 'info',
    }
    return variants[status] || 'default'
}

const getLinkStatusVariant = (status) => {
    const variants = {
        pending: 'warning',
        active: 'success',
        failed: 'error',
        completed: 'info',
    }
    return variants[status] || 'default'
}

const formatDate = (date) => {
    if (!date) return 'N/A'
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    })
}

const startCampaign = () => {
    // TODO: Implement start campaign logic
    console.log('Start campaign')
}

const pauseCampaign = () => {
    // TODO: Implement pause campaign logic
    console.log('Pause campaign')
}

const exportData = () => {
    // TODO: Implement export data logic
    console.log('Export data')
}
</script> 