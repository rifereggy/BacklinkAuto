# BacklinkForge Coding Standards

## 📋 Overview

This document defines coding standards, conventions, and best practices for the BacklinkForge project. All AI agents must follow these standards to ensure code quality, consistency, and maintainability.

---

## 🎯 General Principles

### Code Quality
- **Readability**: Code should be self-documenting and easy to understand
- **Maintainability**: Structure code for long-term maintenance
- **Performance**: Optimize for performance without sacrificing readability
- **Security**: Follow security best practices at all times
- **Testing**: Write tests for all new functionality

### Documentation
- **Inline Comments**: Explain complex logic, not obvious code
- **PHPDoc**: Use proper PHPDoc blocks for all classes, methods, and properties
- **README**: Keep documentation up-to-date
- **API Docs**: Document all public APIs

---

## 🐘 PHP/Laravel Standards

### PSR-12 Compliance
Follow PSR-12 coding standards strictly:

```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Campaign;
use Illuminate\Support\Facades\Log;

class CampaignService
{
    public function __construct(
        private readonly CampaignRepository $repository
    ) {
    }

    public function createCampaign(array $data): Campaign
    {
        // Implementation
    }
}
```

### Laravel Conventions

#### Models
```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campaign extends Model
{
    protected $fillable = [
        'name',
        'status',
        'graph_json',
        'settings',
    ];

    protected $casts = [
        'graph_json' => 'array',
        'settings' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(CampaignNode::class);
    }
}
```

#### Controllers
```php
<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CampaignRequest;
use App\Models\Campaign;
use App\Services\CampaignService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct(
        private readonly CampaignService $campaignService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $campaigns = $this->campaignService->getUserCampaigns($request->user());
        
        return response()->json($campaigns);
    }

    public function store(CampaignRequest $request): JsonResponse
    {
        $campaign = $this->campaignService->createCampaign(
            $request->validated(),
            $request->user()
        );

        return response()->json($campaign, 201);
    }
}
```

#### Services
```php
<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Campaign;
use App\Models\User;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CampaignService
{
    public function __construct(
        private readonly CampaignRepository $repository
    ) {
    }

    public function createCampaign(array $data, User $user): Campaign
    {
        return DB::transaction(function () use ($data, $user) {
            $campaign = $this->repository->create([
                'user_id' => $user->id,
                'team_id' => $user->currentTeam->id,
                'name' => $data['name'],
                'status' => 'draft',
                'graph_json' => $data['graph_json'] ?? [],
                'settings' => $data['settings'] ?? [],
            ]);

            Log::info('Campaign created', [
                'campaign_id' => $campaign->id,
                'user_id' => $user->id,
            ]);

            return $campaign;
        });
    }
}
```

### Database Standards

#### Migrations
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->enum('status', ['draft', 'running', 'completed', 'failed']);
            $table->json('graph_json')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index(['team_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
```

#### Factories
```php
<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CampaignFactory extends Factory
{
    protected $model = Campaign::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'team_id' => fn (array $attributes) => User::find($attributes['user_id'])->currentTeam->id,
            'name' => $this->faker->sentence(3),
            'status' => $this->faker->randomElement(['draft', 'running', 'completed']),
            'graph_json' => [],
            'settings' => [],
        ];
    }
}
```

---

## 🟢 Vue.js Standards

### Component Structure
```vue
<template>
  <div class="campaign-card">
    <h3 class="campaign-title">{{ campaign.name }}</h3>
    <div class="campaign-status">
      <Badge :variant="getStatusVariant(campaign.status)">
        {{ campaign.status }}
      </Badge>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Badge from '@/components/UI/Badge.vue'

// Props
const props = defineProps({
  campaign: {
    type: Object,
    required: true,
  },
})

// Emits
const emit = defineEmits(['edit', 'delete'])

// Computed
const statusVariant = computed(() => getStatusVariant(props.campaign.status))

// Methods
const getStatusVariant = (status) => {
  const variants = {
    draft: 'secondary',
    running: 'primary',
    completed: 'success',
    failed: 'danger',
  }
  return variants[status] || 'secondary'
}

const handleEdit = () => {
  emit('edit', props.campaign)
}

const handleDelete = () => {
  emit('delete', props.campaign)
}
</script>

<style scoped>
.campaign-card {
  @apply bg-surface rounded-lg p-4 border border-border-color;
}

.campaign-title {
  @apply text-lg font-semibold text-text-primary mb-2;
}

.campaign-status {
  @apply flex justify-end;
}
</style>
```

### Composables
```javascript
// composables/useCampaigns.js
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

export function useCampaigns() {
  const campaigns = ref([])
  const loading = ref(false)
  const error = ref(null)

  const activeCampaigns = computed(() => 
    campaigns.value.filter(c => c.status === 'running')
  )

  const fetchCampaigns = async () => {
    loading.value = true
    error.value = null
    
    try {
      const response = await router.get('/api/campaigns')
      campaigns.value = response.data
    } catch (err) {
      error.value = err.message
    } finally {
      loading.value = false
    }
  }

  const createCampaign = async (data) => {
    try {
      const response = await router.post('/api/campaigns', data)
      campaigns.value.push(response.data)
      return response.data
    } catch (err) {
      error.value = err.message
      throw err
    }
  }

  return {
    campaigns,
    loading,
    error,
    activeCampaigns,
    fetchCampaigns,
    createCampaign,
  }
}
```

### Store (Pinia)
```javascript
// stores/campaigns.js
import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCampaignStore = defineStore('campaigns', () => {
  // State
  const campaigns = ref([])
  const currentCampaign = ref(null)
  const loading = ref(false)

  // Getters
  const activeCampaigns = computed(() => 
    campaigns.value.filter(c => c.status === 'running')
  )

  const campaignById = computed(() => (id) => 
    campaigns.value.find(c => c.id === id)
  )

  // Actions
  const setCampaigns = (newCampaigns) => {
    campaigns.value = newCampaigns
  }

  const addCampaign = (campaign) => {
    campaigns.value.push(campaign)
  }

  const updateCampaign = (id, updates) => {
    const index = campaigns.value.findIndex(c => c.id === id)
    if (index !== -1) {
      campaigns.value[index] = { ...campaigns.value[index], ...updates }
    }
  }

  const removeCampaign = (id) => {
    const index = campaigns.value.findIndex(c => c.id === id)
    if (index !== -1) {
      campaigns.value.splice(index, 1)
    }
  }

  return {
    // State
    campaigns,
    currentCampaign,
    loading,
    
    // Getters
    activeCampaigns,
    campaignById,
    
    // Actions
    setCampaigns,
    addCampaign,
    updateCampaign,
    removeCampaign,
  }
})
```

---

## 🟨 JavaScript/Node.js Standards

### Node.js Service Structure
```javascript
// node-automation/src/services/BrowserService.js
const { chromium } = require('playwright')
const logger = require('../utils/logger')

class BrowserService {
  constructor() {
    this.browser = null
    this.context = null
  }

  async initialize(proxy = null) {
    try {
      const launchOptions = {
        headless: true,
        args: [
          '--no-sandbox',
          '--disable-setuid-sandbox',
          '--disable-dev-shm-usage',
          '--disable-accelerated-2d-canvas',
          '--no-first-run',
          '--no-zygote',
          '--disable-gpu',
        ],
      }

      if (proxy) {
        launchOptions.proxy = {
          server: `http://${proxy.ip}:${proxy.port}`,
          username: proxy.username,
          password: proxy.password,
        }
      }

      this.browser = await chromium.launch(launchOptions)
      this.context = await this.browser.newContext({
        userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        viewport: { width: 1920, height: 1080 },
      })

      logger.info('Browser initialized successfully')
    } catch (error) {
      logger.error('Failed to initialize browser', { error: error.message })
      throw error
    }
  }

  async createPage() {
    if (!this.context) {
      throw new Error('Browser context not initialized')
    }

    const page = await this.context.newPage()
    
    // Add stealth measures
    await page.addInitScript(() => {
      Object.defineProperty(navigator, 'webdriver', {
        get: () => undefined,
      })
    })

    return page
  }

  async close() {
    if (this.browser) {
      await this.browser.close()
      this.browser = null
      this.context = null
    }
  }
}

module.exports = BrowserService
```

### Error Handling
```javascript
// node-automation/src/utils/errorHandler.js
const logger = require('./logger')

class ErrorHandler {
  static handle(error, context = {}) {
    logger.error('Error occurred', {
      message: error.message,
      stack: error.stack,
      context,
    })

    return {
      success: false,
      error: error.message,
      code: error.code || 'UNKNOWN_ERROR',
    }
  }

  static async wrapAsync(fn, context = {}) {
    try {
      const result = await fn()
      return { success: true, data: result }
    } catch (error) {
      return this.handle(error, context)
    }
  }
}

module.exports = ErrorHandler
```

---

## 🎨 CSS/Tailwind Standards

### Design Tokens
```css
/* frontend/src/assets/css/tokens.css */
:root {
  /* Colors */
  --bg-primary: #0b0f14;
  --bg-surface: #0f1720;
  --text-primary: #ffffff;
  --text-muted: #a8b3bf;
  --accent-primary: #7c3aed;
  --accent-hover: #6b21a8;
  --border-color: #1e293b;
  
  /* Spacing */
  --spacing-xs: 4px;
  --spacing-sm: 8px;
  --spacing-md: 12px;
  --spacing-lg: 16px;
  --spacing-xl: 24px;
  
  /* Border Radius */
  --radius-sm: 6px;
  --radius-md: 12px;
  --radius-lg: 16px;
  
  /* Shadows */
  --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
  --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1);
  --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1);
}
```

### Component Styles
```css
/* frontend/src/assets/css/components.css */
.btn {
  @apply inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md transition-colors duration-200;
}

.btn-primary {
  @apply bg-accent-primary text-white hover:bg-accent-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent-primary;
}

.btn-secondary {
  @apply bg-transparent text-text-primary border-border-color hover:bg-bg-surface;
}

.card {
  @apply bg-bg-surface border border-border-color rounded-lg p-4 shadow-sm;
}

.input {
  @apply block w-full px-3 py-2 border border-border-color rounded-md bg-bg-surface text-text-primary placeholder-text-muted focus:outline-none focus:ring-2 focus:ring-accent-primary focus:border-transparent;
}
```

---

## 🧪 Testing Standards

### PHP Unit Tests
```php
<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Models\Campaign;
use App\Models\User;
use App\Services\CampaignService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignServiceTest extends TestCase
{
    use RefreshDatabase;

    private CampaignService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(CampaignService::class);
    }

    public function test_can_create_campaign(): void
    {
        $user = User::factory()->create();
        $data = [
            'name' => 'Test Campaign',
            'graph_json' => [],
            'settings' => [],
        ];

        $campaign = $this->service->createCampaign($data, $user);

        $this->assertInstanceOf(Campaign::class, $campaign);
        $this->assertEquals('Test Campaign', $campaign->name);
        $this->assertEquals($user->id, $campaign->user_id);
        $this->assertEquals('draft', $campaign->status);
    }
}
```

### Vue Component Tests
```javascript
// frontend/tests/components/CampaignCard.test.js
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import CampaignCard from '@/components/CampaignCard.vue'

describe('CampaignCard', () => {
  const mockCampaign = {
    id: 1,
    name: 'Test Campaign',
    status: 'running',
  }

  it('renders campaign name correctly', () => {
    const wrapper = mount(CampaignCard, {
      props: { campaign: mockCampaign },
    })

    expect(wrapper.text()).toContain('Test Campaign')
  })

  it('emits edit event when edit button is clicked', async () => {
    const wrapper = mount(CampaignCard, {
      props: { campaign: mockCampaign },
    })

    await wrapper.find('[data-testid="edit-button"]').trigger('click')

    expect(wrapper.emitted('edit')).toBeTruthy()
    expect(wrapper.emitted('edit')[0]).toEqual([mockCampaign])
  })
})
```

---

## 🔒 Security Standards

### Input Validation
```php
<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'graph_json' => ['nullable', 'array'],
            'settings' => ['nullable', 'array'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Campaign name is required.',
            'name.max' => 'Campaign name cannot exceed 255 characters.',
        ];
    }
}
```

### Data Encryption
```php
<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Account extends Model
{
    protected $fillable = [
        'provider',
        'username',
        'email',
        'password_encrypted',
    ];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password_encrypted'] = Crypt::encryptString($value);
    }

    public function getPasswordAttribute(): string
    {
        return Crypt::decryptString($this->password_encrypted);
    }
}
```

---

## 📝 Documentation Standards

### PHPDoc Blocks
```php
/**
 * Service for managing campaign operations.
 *
 * This service handles all business logic related to campaigns,
 * including creation, updates, and status management.
 */
class CampaignService
{
    /**
     * Create a new campaign for the given user.
     *
     * @param array $data Campaign data including name, graph_json, and settings
     * @param User $user The user creating the campaign
     * @return Campaign The created campaign instance
     * @throws \InvalidArgumentException When required data is missing
     * @throws \Exception When campaign creation fails
     */
    public function createCampaign(array $data, User $user): Campaign
    {
        // Implementation
    }
}
```

### API Documentation
```php
/**
 * @OA\Post(
 *     path="/api/campaigns",
 *     summary="Create a new campaign",
 *     tags={"Campaigns"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="My Campaign"),
 *             @OA\Property(property="graph_json", type="object", example={}),
 *             @OA\Property(property="settings", type="object", example={})
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Campaign created successfully",
 *         @OA\JsonContent(ref="#/components/schemas/Campaign")
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error"
 *     )
 * )
 */
public function store(CampaignRequest $request): JsonResponse
{
    // Implementation
}
```

---

## 🔄 Git Standards

### Commit Messages
Follow conventional commits format:

```
type(scope): description

[optional body]

[optional footer]
```

Examples:
```
feat(campaigns): add campaign creation endpoint
fix(auth): resolve session timeout issue
docs(readme): update installation instructions
test(campaigns): add unit tests for CampaignService
refactor(ui): extract common button component
```

### Branch Naming
- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `hotfix/critical-fix` - Critical production fixes
- `refactor/component-name` - Code refactoring
- `docs/documentation-update` - Documentation updates

---

## 📊 Performance Standards

### Database Optimization
- Use database indexes for frequently queried columns
- Implement eager loading to avoid N+1 queries
- Use database transactions for data integrity
- Implement query caching where appropriate

### Frontend Optimization
- Lazy load components and routes
- Implement proper memoization
- Use virtual scrolling for large lists
- Optimize bundle size with code splitting

### Caching Strategy
```php
// Cache frequently accessed data
public function getUserCampaigns(User $user): Collection
{
    return Cache::remember(
        "user_campaigns_{$user->id}",
        now()->addMinutes(15),
        fn() => $user->campaigns()->with('nodes')->get()
    );
}
```

---

## 🚀 Deployment Standards

### Environment Configuration
- Use environment variables for all configuration
- Never commit sensitive data to version control
- Use different configurations for different environments
- Implement proper logging and monitoring

### Docker Best Practices
```dockerfile
# Use multi-stage builds
FROM node:18-alpine AS frontend-builder
WORKDIR /app
COPY frontend/package*.json ./
RUN npm ci --only=production
COPY frontend/ ./
RUN npm run build

FROM php:8.2-fpm-alpine
# ... rest of Dockerfile
```

---

## 📋 Checklist for AI Agents

Before submitting any code, ensure:

### Code Quality
- [ ] Follows PSR-12 standards (PHP)
- [ ] Uses proper TypeScript/Vue conventions
- [ ] Includes proper error handling
- [ ] Implements input validation
- [ ] Uses dependency injection where appropriate

### Testing
- [ ] Unit tests written for new functionality
- [ ] Integration tests for API endpoints
- [ ] Frontend component tests
- [ ] All tests pass

### Documentation
- [ ] PHPDoc blocks for all classes/methods
- [ ] README updated if needed
- [ ] API documentation updated
- [ ] Code comments for complex logic

### Security
- [ ] Input validation implemented
- [ ] Sensitive data encrypted
- [ ] SQL injection prevented
- [ ] XSS protection in place
- [ ] CSRF protection implemented

### Performance
- [ ] Database queries optimized
- [ ] Proper caching implemented
- [ ] Frontend bundle optimized
- [ ] No memory leaks

---

**Last Updated**: January 2024  
**Version**: 1.0.0  
**Status**: Active 