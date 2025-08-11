<template>
    <AppLayout title="Create Campaign">
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-primary">Create Campaign</h2>
                <Button variant="outline" @click="$inertia.visit(route('campaigns.index'))">
                    Back to Campaigns
                </Button>
            </div>
        </template>

        <div class="max-w-4xl mx-auto">
            <Card class="mb-6">
                <template #header>
                    <h3 class="text-lg font-semibold text-primary">Campaign Details</h3>
                </template>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Input
                            v-model="form.name"
                            label="Campaign Name"
                            placeholder="Enter campaign name"
                            :error="form.errors.name"
                            required
                        />

                        <Input
                            v-model="form.status"
                            label="Status"
                            type="select"
                            :options="statusOptions"
                            :error="form.errors.status"
                            required
                        />
                    </div>

                    <Input
                        v-model="form.description"
                        label="Description"
                        type="textarea"
                        placeholder="Describe your campaign"
                        :error="form.errors.description"
                        rows="3"
                    />

                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">
                            Target URLs
                        </label>
                        <div class="space-y-2">
                            <div
                                v-for="(url, index) in form.target_urls"
                                :key="index"
                                class="flex gap-2"
                            >
                                <Input
                                    v-model="form.target_urls[index]"
                                    :placeholder="`Target URL ${index + 1}`"
                                    :error="form.errors[`target_urls.${index}`]"
                                    class="flex-1"
                                />
                                <Button
                                    v-if="form.target_urls.length > 1"
                                    variant="outline"
                                    size="sm"
                                    @click="removeUrl(index)"
                                    type="button"
                                >
                                    Remove
                                </Button>
                            </div>
                        </div>
                        <Button
                            variant="outline"
                            size="sm"
                            @click="addUrl"
                            type="button"
                            class="mt-2"
                        >
                            Add URL
                        </Button>
                        <p v-if="form.errors.target_urls" class="text-error text-sm mt-1">
                            {{ form.errors.target_urls }}
                        </p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Button
                            variant="outline"
                            @click="$inertia.visit(route('campaigns.index'))"
                            type="button"
                        >
                            Cancel
                        </Button>
                        <Button type="submit" :loading="form.processing">
                            Create Campaign
                        </Button>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Input from '@/Components/UI/Input.vue'

const statusOptions = [
    { value: 'draft', label: 'Draft' },
    { value: 'active', label: 'Active' },
    { value: 'paused', label: 'Paused' },
    { value: 'completed', label: 'Completed' },
]

const form = useForm({
    name: '',
    description: '',
    target_urls: [''],
    status: 'draft',
})

const addUrl = () => {
    form.target_urls.push('')
}

const removeUrl = (index) => {
    form.target_urls.splice(index, 1)
}

const submit = () => {
    form.post(route('campaigns.store'))
}
</script> 