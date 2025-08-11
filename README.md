# BacklinkForge

A comprehensive SaaS multi-tenant backlink automation platform with visual tier builder, automation engine, and comprehensive admin panel.

## 🚀 Quick Start

### Prerequisites

- Docker & Docker Compose
- Node.js 18+
- PHP 8.2+
- Composer

### Development Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd backlinkforge
   ```

2. **Copy environment file**
   ```bash
   cp env.example .env
   ```

3. **Start Docker services**
   ```bash
   docker-compose up -d
   ```

4. **Install PHP dependencies**
   ```bash
   composer install
   ```

5. **Install Node.js dependencies**
   ```bash
   npm install
   cd node-automation && npm install && cd ..
   ```

6. **Generate application key**
   ```bash
   php artisan key:generate
   ```

7. **Run database migrations**
   ```bash
   php artisan migrate
   ```

8. **Build frontend assets**
   ```bash
   npm run build
   ```

### Access Points

- **Main Application**: http://localhost:8000
- **Admin Panel**: http://localhost:8000/admin
- **Horizon Dashboard**: http://localhost:8000/horizon
- **MinIO Console**: http://localhost:9001
- **MailHog**: http://localhost:8025
- **Automation Service**: http://localhost:3000

## 🏗️ Architecture

### Tech Stack

- **Backend**: Laravel 12+ with PHP 8.2+
- **Frontend**: Vue 3 + Vite + Inertia.js
- **Authentication**: Laravel Jetstream (Inertia + Vue)
- **Database**: MySQL 8
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

## 🎨 Features

### Core Features

- **Visual Tier Builder**: Drag-and-drop interface for designing backlink campaigns
- **Automation Engine**: Node.js + Playwright for account creation and content posting
- **Multi-Tenant Architecture**: Team-based isolation with Laravel Jetstream
- **Proxy Management**: Rotating proxy pools with health monitoring
- **Captcha Solving**: Tesseract OCR + 3rd-party fallback
- **Content Generation**: Spintax + AI integration (OpenAI)
- **Queue System**: Redis + Laravel Horizon for job orchestration
- **Admin Panel**: Comprehensive monitoring and management tools

### Node Types

- **Web2.0**: WordPress, Blogger, Medium
- **Wiki**: MediaWiki, DokuWiki, Wikidot
- **Forum**: phpBB, vBulletin, XenForo
- **Profile**: Social networks, directories
- **Bookmark**: Delicious, StumbleUpon
- **RSS**: Feed directories, aggregators
- **PDF**: Document sharing sites

## 🔧 Configuration

### Environment Variables

Key environment variables for development:

```env
# Database
DB_HOST=mysql
DB_DATABASE=backlinkforge
DB_USERNAME=root
DB_PASSWORD=password

# Redis
REDIS_HOST=redis
REDIS_PASSWORD=

# Automation Service
PLAYWRIGHT_SERVICE_URL=http://localhost:3000

# Storage (MinIO for development)
AWS_ENDPOINT=http://localhost:9000
AWS_BUCKET=backlinkforge
AWS_ACCESS_KEY_ID=minioadmin
AWS_SECRET_ACCESS_KEY=minioadmin
```

## 🧪 Testing

### Running Tests

```bash
# PHP Tests
php artisan test

# Frontend Tests
npm test

# Node.js Automation Tests
cd node-automation && npm test
```

### Test Coverage

```bash
# PHP Coverage
php artisan test --coverage

# Frontend Coverage
npm run test:coverage
```

## 🚀 Deployment

### Production Deployment

1. **Build Docker images**
   ```bash
   docker-compose -f docker-compose.prod.yml build
   ```

2. **Deploy to production**
   ```bash
   docker-compose -f docker-compose.prod.yml up -d
   ```

3. **Run migrations**
   ```bash
   docker-compose -f docker-compose.prod.yml exec app php artisan migrate
   ```

### Environment Setup

- Configure production environment variables
- Set up SSL certificates
- Configure database backups
- Enable monitoring and logging

## 📚 Documentation

- **[Implementation Plan](docs/IMPLEMENTATION.md)** - Detailed implementation plan with milestones
- **[API Documentation](docs/API.md)** - API specifications and endpoints
- **[Coding Standards](docs/CODING_STANDARDS.md)** - Code style and conventions
- **[User Guide](docs/USER_GUIDE.md)** - End-user documentation
- **[Changelog](docs/CHANGELOG.md)** - Project changelog and updates

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines

- Follow the coding standards in `docs/CODING_STANDARDS.md`
- Write tests for all new functionality
- Update documentation as needed
- Use conventional commit messages

## 📄 License

This project is proprietary software. All rights reserved.

## 🆘 Support

For support and questions:

- **Documentation**: Check the `docs/` directory
- **Issues**: Create an issue in the repository
- **Email**: support@backlinkforge.com

---

**BacklinkForge** - Professional Backlink Automation Platform

**Version**: 1.0.0  
**Status**: In Development  
**Last Updated**: January 2024 