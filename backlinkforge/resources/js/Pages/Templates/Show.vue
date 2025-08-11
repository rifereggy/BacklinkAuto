<template>
    <AppLayout :title="template.name">
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-primary">{{ template.name }}</h2>
                    <p class="text-muted">{{ template.description }}</p>
                </div>
                <div class="flex gap-3">
                    <Button
                        variant="outline"
                        @click="$inertia.visit(route('templates.edit', template.id))"
                    >
                        Edit Template
                    </Button>
                    <Button
                        variant="outline"
                        @click="$inertia.visit(route('templates.index'))"
                    >
                        Back to Templates
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <!-- Template Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary">{{ template.campaigns_count || 0 }}</div>
                        <div class="text-sm text-muted">Campaigns Used</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-success">{{ template.links_created || 0 }}</div>
                        <div class="text-sm text-muted">Links Created</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-info">{{ template.success_rate || 0 }}%</div>
                        <div class="text-sm text-muted">Success Rate</div>
                    </div>
                </Card>
                <Card>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-warning">{{ template.variables?.length || 0 }}</div>
                        <div class="text-sm text-muted">Variables</div>
                    </div>
                </Card>
            </div>

            <!-- Template Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2">
                    <Card>
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Template Content</h3>
                        </template>

                        <div class="space-y-4">
                            <div class="bg-surface p-4 rounded-md">
                                <pre class="text-sm text-secondary whitespace-pre-wrap">{{ template.content }}</pre>
                            </div>
                        </div>
                    </Card>

                    <!-- Template Variables -->
                    <Card class="mt-6" v-if="template.variables?.length">
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Template Variables</h3>
                        </template>

                        <div class="space-y-3">
                            <div
                                v-for="variable in template.variables"
                                :key="variable.name"
                                class="flex items-center justify-between p-3 bg-surface rounded-md"
                            >
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <span class="font-medium text-primary">{{ variable.name }}</span>
                                        <Badge :variant="variable.required ? 'error' : 'default'" size="sm">
                                            {{ variable.required ? 'Required' : 'Optional' }}
                                        </Badge>
                                        <Badge variant="info" size="sm">{{ variable.type }}</Badge>
                                    </div>
                                    <div v-if="variable.options?.length" class="text-sm text-muted mt-1">
                                        Options: {{ variable.options.join(', ') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Template Details -->
                <div>
                    <Card>
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Template Information</h3>
                        </template>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Category:</span>
                                <Badge variant="default">{{ template.category }}</Badge>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Visibility:</span>
                                <Badge :variant="template.is_public ? 'success' : 'default'">
                                    {{ template.is_public ? 'Public' : 'Private' }}
                                </Badge>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Created:</span>
                                <span>{{ formatDate(template.created_at) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted">Last Updated:</span>
                                <span>{{ formatDate(template.updated_at) }}</span>
                            </div>
                        </div>
                    </Card>

                    <!-- Recent Campaigns -->
                    <Card class="mt-6">
                        <template #header>
                            <h3 class="text-lg font-semibold text-primary">Recent Campaigns</h3>
                        </template>

                        <div class="space-y-4">
                            <div v-if="template.campaigns?.length" class="space-y-3">
                                <div
                                    v-for="campaign in template.campaigns.slice(0, 5)"
                                    :key="campaign.id"
                                    class="flex items-center gap-3 p-3 bg-surface rounded-md"
                                >
                                    <div class="flex-1">
                                        <div class="text-sm font-medium text-primary">
                                            {{ campaign.name }}
                                        </div>
                                        <div class="text-xs text-muted">
                                            {{ formatDate(campaign.created_at) }}
                                        </div>
                                    </div>
                                    <Badge :variant="getCampaignStatusVariant(campaign.status)" size="sm">
                                        {{ campaign.status }}
                                    </Badge>
                                </div>
                            </div>
                            <div v-else class="text-center py-8">
                                <div class="text-muted">No campaigns yet</div>
                                <div class="text-sm text-muted mt-1">
                                    This template hasn't been used in any campaigns
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
                                @click="createCampaign"
                            >
                                🚀 Create Campaign
                            </Button>
                            <Button
                                variant="outline"
                                class="w-full justify-start"
                                @click="duplicateTemplate"
                            >
                                📋 Duplicate Template
                            </Button>
                            <Button
                                variant="outline"
                                class="w-full justify-start"
                                @click="exportTemplate"
                            >
                                📤 Export Template
                            </Button>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'

const props = defineProps({
    template: {
        type: Object,
        required: true,
    },
})

const getCampaignStatusVariant = (status) => {
    const variants = {
        draft: 'default',
        active: 'success',
        paused: 'warning',
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

const createCampaign = () => {
    // TODO: Implement create campaign with this template
    console.log('Create campaign with template')
}

const duplicateTemplate = () => {
    // TODO: Implement duplicate template logic
    console.log('Duplicate template')
}

const exportTemplate = () => {
    // TODO: Implement export template logic
    console.log('Export template')
}
</script> 