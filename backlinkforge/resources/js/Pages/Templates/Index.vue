<template>
  <AppLayout title="Templates">
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
          <div>
            <h1 class="text-3xl font-bold text-white">Templates</h1>
            <p class="mt-2 text-dark-400">Pre-built campaign templates to get you started quickly</p>
          </div>
          <Button @click="createTemplate">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Template
          </Button>
        </div>

        <!-- Categories -->
        <div class="mb-6">
          <div class="flex flex-wrap gap-2">
            <button
              v-for="category in categories"
              :key="category.id"
              @click="selectedCategory = category.id"
              :class="[
                'px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200',
                selectedCategory === category.id
                  ? 'bg-primary-600 text-white'
                  : 'bg-dark-700 text-dark-300 hover:bg-dark-600 hover:text-white'
              ]"
            >
              {{ category.name }}
            </button>
          </div>
        </div>

        <!-- Templates Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <Card
            v-for="template in filteredTemplates"
            :key="template.id"
            class="hover:shadow-lg transition-all duration-200 cursor-pointer group"
            @click="viewTemplate(template.id)"
          >
            <!-- Template Preview -->
            <div class="relative mb-4">
              <div class="aspect-video bg-gradient-to-br from-primary-600 to-primary-800 rounded-lg flex items-center justify-center">
                <svg class="w-12 h-12 text-white opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                </svg>
              </div>
              <div class="absolute top-2 right-2">
                <Badge :variant="template.is_public ? 'info' : 'default'" size="sm">
                  {{ template.is_public ? 'Public' : 'Private' }}
                </Badge>
              </div>
            </div>

            <!-- Template Info -->
            <div class="mb-4">
              <h3 class="text-lg font-semibold text-white mb-2">{{ template.name }}</h3>
              <p class="text-sm text-dark-400 mb-3">{{ template.description }}</p>
              
              <div class="flex items-center justify-between text-sm">
                <span class="text-dark-400">Nodes:</span>
                <span class="text-white font-medium">{{ template.node_count }}</span>
              </div>
              <div class="flex items-center justify-between text-sm">
                <span class="text-dark-400">Difficulty:</span>
                <div class="flex items-center">
                  <div class="flex space-x-1">
                    <div
                      v-for="i in 5"
                      :key="i"
                      :class="[
                        'w-2 h-2 rounded-full',
                        i <= template.difficulty ? 'bg-warning-500' : 'bg-dark-600'
                      ]"
                    ></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Template Stats -->
            <div class="flex items-center justify-between text-xs text-dark-400 mb-4">
              <span>Used {{ template.usage_count }} times</span>
              <span>{{ template.created_at }}</span>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-dark-700">
              <div class="flex space-x-2">
                <Button
                  size="sm"
                  variant="ghost"
                  @click.stop="previewTemplate(template.id)"
                >
                  Preview
                </Button>
                <Button
                  size="sm"
                  variant="ghost"
                  @click.stop="duplicateTemplate(template.id)"
                >
                  Duplicate
                </Button>
              </div>
              <Button
                size="sm"
                @click.stop="useTemplate(template.id)"
              >
                Use Template
              </Button>
            </div>
          </Card>
        </div>

        <!-- Empty State -->
        <div v-if="filteredTemplates.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-white">No templates found</h3>
          <p class="mt-1 text-sm text-dark-400">
            {{ selectedCategory !== 'all' ? 'No templates in this category.' : 'Get started by creating your first template.' }}
          </p>
          <div class="mt-6">
            <Button @click="createTemplate">
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Create Template
            </Button>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppLayout from '@/Components/Layout/AppLayout.vue'
import Card from '@/Components/UI/Card.vue'
import Button from '@/Components/UI/Button.vue'
import Badge from '@/Components/UI/Badge.vue'

// Categories
const categories = ref([
  { id: 'all', name: 'All Templates' },
  { id: 'web2', name: 'Web 2.0' },
  { id: 'wiki', name: 'Wiki' },
  { id: 'forum', name: 'Forum' },
  { id: 'profile', name: 'Profile' },
  { id: 'bookmark', name: 'Bookmark' },
  { id: 'rss', name: 'RSS' },
  { id: 'pdf', name: 'PDF' }
])

// Mock templates data
const templates = ref([
  {
    id: 1,
    name: 'Tech Blog Pyramid',
    description: 'Complete backlink pyramid for technology websites',
    category: 'web2',
    is_public: true,
    node_count: 15,
    difficulty: 3,
    usage_count: 127,
    created_at: '2 weeks ago'
  },
  {
    id: 2,
    name: 'Local Business Blitz',
    description: 'Local directory and citation building template',
    category: 'profile',
    is_public: true,
    node_count: 8,
    difficulty: 2,
    usage_count: 89,
    created_at: '1 month ago'
  },
  {
    id: 3,
    name: 'E-commerce Power Links',
    description: 'Product review and affiliate link strategy',
    category: 'web2',
    is_public: false,
    node_count: 12,
    difficulty: 4,
    usage_count: 45,
    created_at: '3 days ago'
  },
  {
    id: 4,
    name: 'Wiki Authority Builder',
    description: 'Wikipedia and wiki-based backlink strategy',
    category: 'wiki',
    is_public: true,
    node_count: 6,
    difficulty: 5,
    usage_count: 203,
    created_at: '1 week ago'
  },
  {
    id: 5,
    name: 'Forum Engagement',
    description: 'Forum participation and signature link building',
    category: 'forum',
    is_public: true,
    node_count: 10,
    difficulty: 3,
    usage_count: 156,
    created_at: '2 months ago'
  },
  {
    id: 6,
    name: 'Social Bookmarking',
    description: 'Social bookmarking and content sharing strategy',
    category: 'bookmark',
    is_public: true,
    node_count: 7,
    difficulty: 1,
    usage_count: 78,
    created_at: '1 week ago'
  }
])

const selectedCategory = ref('all')

const filteredTemplates = computed(() => {
  if (selectedCategory.value === 'all') {
    return templates.value
  }
  return templates.value.filter(template => template.category === selectedCategory.value)
})

const createTemplate = () => {
  console.log('Create new template')
}

const viewTemplate = (id) => {
  console.log('View template:', id)
}

const previewTemplate = (id) => {
  console.log('Preview template:', id)
}

const duplicateTemplate = (id) => {
  console.log('Duplicate template:', id)
}

const useTemplate = (id) => {
  console.log('Use template:', id)
}
</script> 