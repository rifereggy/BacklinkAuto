# BacklinkForge - SaaS Multi-Tenant Backlink Automation Platform

## 🎯 Project Overview

BacklinkForge is a comprehensive SaaS web-based multi-tenant application for automated backlink building with visual tier builder, automation engine, and comprehensive admin panel.

### Core Features
- **Visual Tier Builder**: Drag-and-drop interface for designing backlink campaigns
- **Automation Engine**: Node.js + Playwright for account creation and content posting
- **Multi-Tenant Architecture**: Team-based isolation with Laravel Jetstream
- **Proxy Management**: Rotating proxy pools with health monitoring
- **Captcha Solving**: Tesseract OCR + 3rd-party fallback
- **Content Generation**: Spintax + AI integration (OpenAI)
- **Queue System**: Redis + Laravel Horizon for job orchestration
- **Admin Panel**: Comprehensive monitoring and management tools

## 🏗️ Architecture

### Tech Stack
- **Backend**: Laravel 10+ with PHP 8.2+
- **Frontend**: Vue 3 + Vite + Inertia.js
- **Authentication**: Laravel Jetstream (Inertia + Vue)
- **Database**: MySQL 8 / MariaDB
- **Cache/Queue**: Redis
- **Automation**: Node.js + Playwright
- **UI**: Tailwind CSS + Dark Theme
- **Deployment**: Docker + Docker Compose

### Service Architecture
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Laravel App   │    │  Node.js Auto   │    │     Redis       │
│   (Main App)    │◄──►│   (Playwright)  │◄──►│   (Queue/Cache) │
└─────────────────┘    └─────────────────┘    └─────────────────┘
         │                       │                       │
         ▼                       ▼                       ▼
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   MySQL DB      │    │   Proxy Pool    │    │   MinIO/S3      │
│   (Data Store)  │    │   (Rotation)    │    │   (Storage)     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

## 📁 Project Structure

```
/backlinkforge/
├── app/                          # Laravel application
│   ├── Http/Controllers/         # API controllers
│   ├── Models/                   # Eloquent models
│   ├── Jobs/                     # Queue jobs
│   └── Services/                 # Business logic services
├── frontend/                     # Vue 3 frontend
│   ├── src/
│   │   ├── components/           # Vue components
│   │   ├── pages/                # Inertia pages
│   │   ├── composables/          # Vue composables
│   │   └── assets/               # CSS, JS assets
│   └── package.json
├── node-automation/              # Node.js automation service
│   ├── src/
│   │   ├── services/             # Automation services
│   │   ├── providers/            # Site-specific providers
│   │   └── queue/                # Queue consumers
│   └── package.json
├── database/
│   └── migrations/               # Database migrations
├── docs/                         # Documentation
├── tests/                        # Test suites
├── docker/                       # Docker configurations
├── docker-compose.yml
└── README.md
```

## 🎨 UI/UX Design System

### Dark Theme Palette
```css
--bg-primary: #0b0f14      /* Main background */
--bg-surface: #0f1720      /* Card/surface background */
--text-primary: #ffffff     /* Primary text */
--text-muted: #a8b3bf      /* Muted text */
--accent-primary: #7c3aed  /* Primary accent (purple) */
--accent-hover: #6b21a8    /* Hover state */
--border-color: #1e293b    /* Borders */
```

### Typography
- **Font**: Inter variable
- **Sizes**: h1: 20-24px, body: 14px
- **Weights**: Regular (400), Medium (500), Semibold (600)

### Components
- **Cards**: 12px padding, 12px radius, subtle shadow
- **Buttons**: Primary filled (accent), secondary ghost
- **Modals**: 2xl rounded corners
- **Layout**: Left nav (icons + labels), main content, right inspector

## 🔄 Development Workflow

### For AI Agents
1. **Read this README.md first** - Understand project goals and architecture
2. **Check docs/IMPLEMENTATION.md** - Detailed implementation plan
3. **Review docs/API.md** - API specifications and endpoints
4. **Follow docs/CODING_STANDARDS.md** - Code style and conventions
5. **Update docs/CHANGELOG.md** - Document all changes

### Development Phases
1. **Foundation** - Project setup, Docker, basic Laravel + Vue
2. **Core Backend** - Models, migrations, API endpoints
3. **Frontend** - Dark theme, layout, basic pages
4. **Tier Builder** - Visual drag-and-drop editor
5. **Automation** - Node.js service, Playwright integration
6. **Advanced Features** - Captcha, content generation, admin panel
7. **Testing & Deploy** - Tests, documentation, deployment

## 🚀 Quick Start

### Prerequisites
- Docker & Docker Compose
- Node.js 18+
- PHP 8.2+
- Composer

### Development Setup
```bash
# Clone and setup
git clone <repository>
cd backlinkforge

# Start services
docker-compose up -d

# Install dependencies
composer install
npm install --prefix frontend
npm install --prefix node-automation

# Run migrations
php artisan migrate

# Build frontend
npm run build --prefix frontend

# Start automation service
npm start --prefix node-automation
```

### Access Points
- **Main App**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Horizon Dashboard**: http://localhost:8000/horizon
- **API Documentation**: http://localhost:8000/api/docs

## 📋 Acceptance Criteria

### Core Functionality
- [ ] User can create 3-tier campaign and run it successfully
- [ ] Automation creates accounts and posts content
- [ ] Link validation returns URL and status
- [ ] Admin can manage proxies and view metrics
- [ ] All credentials are encrypted
- [ ] UI is responsive and dark-themed
- [ ] System is production-ready with monitoring

### Technical Requirements
- [ ] Multi-tenant data isolation
- [ ] Queue system with job orchestration
- [ ] Proxy rotation and health monitoring
- [ ] Captcha solving pipeline
- [ ] Content generation (spintax + AI)
- [ ] Comprehensive logging and monitoring
- [ ] Security best practices implemented

## 🔧 Configuration

### Environment Variables
```env
# Laravel
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:...
DB_HOST=mysql
DB_DATABASE=backlinkforge
DB_USERNAME=root
DB_PASSWORD=password
REDIS_HOST=redis
REDIS_PASSWORD=

# Automation Service
PLAYWRIGHT_SERVICE_URL=http://localhost:3000
PROXY_PROVIDER_API_KEY=
CAPTCHA_2CAPTCHA_KEY=
OPENAI_API_KEY=

# External Services
STRIPE_SECRET_KEY=
STRIPE_WEBHOOK_SECRET=
```

## 📚 Documentation Index

- **[IMPLEMENTATION.md](IMPLEMENTATION.md)** - Detailed implementation plan with milestones
- **[API.md](API.md)** - API specifications and endpoints
- **[CODING_STANDARDS.md](CODING_STANDARDS.md)** - Code style and conventions
- **[DEPLOYMENT.md](DEPLOYMENT.md)** - Deployment and production setup
- **[USER_GUIDE.md](USER_GUIDE.md)** - End-user documentation
- **[CHANGELOG.md](CHANGELOG.md)** - Project changelog and updates

## 🤝 Contributing

### For AI Agents
1. Follow the implementation plan in `docs/IMPLEMENTATION.md`
2. Maintain code quality standards in `docs/CODING_STANDARDS.md`
3. Update documentation as you implement features
4. Write tests for all new functionality
5. Ensure all acceptance criteria are met

### Code Quality
- Follow Laravel and Vue.js best practices
- Write comprehensive tests
- Document all public APIs
- Use type hints and return types
- Follow PSR-12 coding standards

## 📄 License

This project is proprietary software. All rights reserved.

---

**Last Updated**: January 2024
**Version**: 1.0.0
**Status**: In Development 