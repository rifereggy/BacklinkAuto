# BacklinkForge API Documentation

## 📋 Overview

This document provides comprehensive API documentation for the BacklinkForge application. All endpoints follow RESTful conventions and return JSON responses.

### Base URL
- **Development**: `http://localhost:8000/api`
- **Production**: `https://your-domain.com/api`

### Authentication
All API endpoints require authentication using Laravel Sanctum tokens.

```bash
# Include in request headers
Authorization: Bearer {your-token}
```

### Response Format
All responses follow this standard format:

```json
{
  "success": true,
  "data": {},
  "message": "Operation successful",
  "meta": {
    "pagination": {}
  }
}
```

### Error Responses
```json
{
  "success": false,
  "error": "Error message",
  "code": "ERROR_CODE",
  "details": {}
}
```

---

## 🔐 Authentication Endpoints

### POST /auth/login
Authenticate user and get access token.

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password",
  "device_name": "Web Browser"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "current_team": {
        "id": 1,
        "name": "My Team"
      }
    },
    "token": "1|abc123..."
  }
}
```

### POST /auth/logout
Logout user and invalidate token.

**Response:**
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

### GET /auth/user
Get current authenticated user.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "current_team": {
      "id": 1,
      "name": "My Team"
    },
    "teams": [
      {
        "id": 1,
        "name": "My Team",
        "role": "admin"
      }
    ]
  }
}
```

---

## 🎯 Campaign Endpoints

### GET /campaigns
Get all campaigns for the authenticated user.

**Query Parameters:**
- `status` (optional): Filter by status (draft, running, completed, failed)
- `page` (optional): Page number for pagination
- `per_page` (optional): Items per page (default: 15)

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "My Campaign",
      "status": "draft",
      "graph_json": {},
      "settings": {},
      "created_at": "2024-01-01T00:00:00Z",
      "updated_at": "2024-01-01T00:00:00Z",
      "nodes_count": 5,
      "accounts_count": 10
    }
  ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 25,
      "last_page": 2
    }
  }
}
```

### POST /campaigns
Create a new campaign.

**Request Body:**
```json
{
  "name": "My Campaign",
  "graph_json": {
    "nodes": [],
    "connections": []
  },
  "settings": {
    "auto_start": false,
    "proxy_rotation": "round_robin"
  }
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "My Campaign",
    "status": "draft",
    "graph_json": {},
    "settings": {},
    "created_at": "2024-01-01T00:00:00Z",
    "updated_at": "2024-01-01T00:00:00Z"
  },
  "message": "Campaign created successfully"
}
```

### GET /campaigns/{id}
Get a specific campaign by ID.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "My Campaign",
    "status": "draft",
    "graph_json": {
      "nodes": [
        {
          "id": "node-1",
          "type": "web2.0",
          "position": { "x": 100, "y": 100 },
          "config": {
            "provider": "wordpress",
            "url": "https://example.com"
          }
        }
      ],
      "connections": [
        {
          "id": "conn-1",
          "source": "node-1",
          "target": "node-2"
        }
      ]
    },
    "settings": {},
    "nodes": [
      {
        "id": 1,
        "campaign_id": 1,
        "node_type": "web2.0",
        "position": "{\"x\":100,\"y\":100}",
        "config": {},
        "created_at": "2024-01-01T00:00:00Z"
      }
    ],
    "accounts": [
      {
        "id": 1,
        "provider": "wordpress",
        "username": "user123",
        "email": "user@example.com",
        "status": "active",
        "created_at": "2024-01-01T00:00:00Z"
      }
    ],
    "created_at": "2024-01-01T00:00:00Z",
    "updated_at": "2024-01-01T00:00:00Z"
  }
}
```

### PUT /campaigns/{id}
Update a campaign.

**Request Body:**
```json
{
  "name": "Updated Campaign Name",
  "graph_json": {
    "nodes": [],
    "connections": []
  },
  "settings": {
    "auto_start": true
  }
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Updated Campaign Name",
    "status": "draft",
    "graph_json": {},
    "settings": {},
    "updated_at": "2024-01-01T00:00:00Z"
  },
  "message": "Campaign updated successfully"
}
```

### DELETE /campaigns/{id}
Delete a campaign.

**Response:**
```json
{
  "success": true,
  "message": "Campaign deleted successfully"
}
```

### POST /campaigns/{id}/start
Start a campaign execution.

**Response:**
```json
{
  "success": true,
  "data": {
    "job_id": "job-123",
    "status": "queued"
  },
  "message": "Campaign started successfully"
}
```

### POST /campaigns/{id}/stop
Stop a running campaign.

**Response:**
```json
{
  "success": true,
  "message": "Campaign stopped successfully"
}
```

---

## 📋 Template Endpoints

### GET /templates
Get all available templates.

**Query Parameters:**
- `is_public` (optional): Filter public templates (true/false)
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Starter Template",
      "description": "Basic 3-tier backlink structure",
      "graph_json": {
        "nodes": [],
        "connections": []
      },
      "is_public": true,
      "created_by": 1,
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

### POST /templates
Create a new template.

**Request Body:**
```json
{
  "name": "My Template",
  "description": "Custom template description",
  "graph_json": {
    "nodes": [],
    "connections": []
  },
  "is_public": false
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "My Template",
    "description": "Custom template description",
    "graph_json": {},
    "is_public": false,
    "created_by": 1,
    "created_at": "2024-01-01T00:00:00Z"
  },
  "message": "Template created successfully"
}
```

### GET /templates/{id}
Get a specific template.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Starter Template",
    "description": "Basic 3-tier backlink structure",
    "graph_json": {
      "nodes": [],
      "connections": []
    },
    "is_public": true,
    "created_by": 1,
    "created_at": "2024-01-01T00:00:00Z"
  }
}
```

---

## 🔧 Proxy Endpoints

### GET /proxies
Get all proxies (admin only).

**Query Parameters:**
- `type` (optional): Filter by type (http, https, socks5)
- `status` (optional): Filter by status (active, inactive, failed)
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "ip": "192.168.1.1",
      "port": 8080,
      "type": "http",
      "country": "US",
      "last_used": "2024-01-01T00:00:00Z",
      "status": "active",
      "success_rate": 95.5,
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

### POST /proxies
Add a new proxy (admin only).

**Request Body:**
```json
{
  "ip": "192.168.1.1",
  "port": 8080,
  "username": "user",
  "password": "pass",
  "type": "http",
  "country": "US"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ip": "192.168.1.1",
    "port": 8080,
    "type": "http",
    "country": "US",
    "status": "active",
    "created_at": "2024-01-01T00:00:00Z"
  },
  "message": "Proxy added successfully"
}
```

### PUT /proxies/{id}
Update a proxy (admin only).

**Request Body:**
```json
{
  "status": "inactive",
  "country": "CA"
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "ip": "192.168.1.1",
    "port": 8080,
    "type": "http",
    "country": "CA",
    "status": "inactive",
    "updated_at": "2024-01-01T00:00:00Z"
  },
  "message": "Proxy updated successfully"
}
```

### DELETE /proxies/{id}
Delete a proxy (admin only).

**Response:**
```json
{
  "success": true,
  "message": "Proxy deleted successfully"
}
```

### POST /proxies/{id}/test
Test proxy connectivity (admin only).

**Response:**
```json
{
  "success": true,
  "data": {
    "status": "success",
    "response_time": 150,
    "ip_detected": "192.168.1.1"
  },
  "message": "Proxy test completed"
}
```

---

## 📊 Analytics Endpoints

### GET /analytics/dashboard
Get dashboard analytics.

**Response:**
```json
{
  "success": true,
  "data": {
    "campaigns": {
      "total": 25,
      "active": 5,
      "completed": 15,
      "failed": 5
    },
    "accounts": {
      "total": 150,
      "active": 120,
      "failed": 30
    },
    "links": {
      "total": 500,
      "indexed": 450,
      "pending": 50
    },
    "performance": {
      "success_rate": 85.5,
      "avg_completion_time": "2h 30m"
    },
    "recent_activity": [
      {
        "id": 1,
        "type": "campaign_started",
        "campaign_name": "My Campaign",
        "created_at": "2024-01-01T00:00:00Z"
      }
    ]
  }
}
```

### GET /analytics/campaigns/{id}
Get campaign-specific analytics.

**Response:**
```json
{
  "success": true,
  "data": {
    "campaign": {
      "id": 1,
      "name": "My Campaign",
      "status": "running",
      "progress": 65
    },
    "nodes": [
      {
        "id": 1,
        "type": "web2.0",
        "status": "completed",
        "accounts_created": 10,
        "posts_made": 8,
        "links_created": 8
      }
    ],
    "timeline": [
      {
        "timestamp": "2024-01-01T00:00:00Z",
        "event": "campaign_started",
        "details": "Campaign execution began"
      }
    ]
  }
}
```

---

## 🔄 Job Endpoints

### GET /jobs
Get job history.

**Query Parameters:**
- `campaign_id` (optional): Filter by campaign ID
- `status` (optional): Filter by status (pending, running, completed, failed)
- `type` (optional): Filter by job type
- `page` (optional): Page number for pagination

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "campaign_id": 1,
      "node_id": 1,
      "job_type": "create_account",
      "status": "completed",
      "payload": {},
      "result": {
        "success": true,
        "account_id": 1
      },
      "attempts": 1,
      "created_at": "2024-01-01T00:00:00Z",
      "completed_at": "2024-01-01T00:05:00Z"
    }
  ]
}
```

### GET /jobs/{id}
Get specific job details.

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "campaign_id": 1,
    "node_id": 1,
    "job_type": "create_account",
    "status": "completed",
    "payload": {
      "provider": "wordpress",
      "username": "user123",
      "email": "user@example.com"
    },
    "result": {
      "success": true,
      "account_id": 1,
      "url": "https://example.com/user123"
    },
    "attempts": 1,
    "error_log": [],
    "created_at": "2024-01-01T00:00:00Z",
    "completed_at": "2024-01-01T00:05:00Z"
  }
}
```

---

## 📝 Content Endpoints

### POST /content/generate
Generate content using spintax or AI.

**Request Body:**
```json
{
  "type": "spintax", // or "ai"
  "content": "{Hello|Hi|Greetings} {world|everyone|folks}!",
  "ai_prompt": "Write a blog post about SEO", // required if type is "ai"
  "max_tokens": 500
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "generated_text": "Hello world!",
    "tokens_used": 5,
    "method": "spintax"
  }
}
```

### GET /content/templates
Get content templates.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Blog Post Template",
      "content": "{Title|Heading|Header} for {keyword|topic|subject}",
      "category": "blog",
      "created_at": "2024-01-01T00:00:00Z"
    }
  ]
}
```

---

## 🔧 Settings Endpoints

### GET /settings
Get application settings.

**Response:**
```json
{
  "success": true,
  "data": {
    "captcha": {
      "tesseract_enabled": true,
      "fallback_service": "2captcha",
      "api_key": "***"
    },
    "automation": {
      "max_concurrent_jobs": 5,
      "retry_attempts": 3,
      "delay_between_actions": 2000
    },
    "content": {
      "ai_enabled": true,
      "max_tokens_per_request": 1000
    }
  }
}
```

### PUT /settings
Update application settings (admin only).

**Request Body:**
```json
{
  "captcha": {
    "tesseract_enabled": true,
    "fallback_service": "2captcha"
  },
  "automation": {
    "max_concurrent_jobs": 10
  }
}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "captcha": {
      "tesseract_enabled": true,
      "fallback_service": "2captcha"
    },
    "automation": {
      "max_concurrent_jobs": 10
    }
  },
  "message": "Settings updated successfully"
}
```

---

## 🔔 Webhook Endpoints

### POST /webhooks/automation
Webhook endpoint for automation service to report job completion.

**Request Body:**
```json
{
  "job_id": "job-123",
  "status": "completed",
  "result": {
    "success": true,
    "account_id": 1,
    "url": "https://example.com/post"
  },
  "error": null,
  "screenshot": "base64_encoded_image"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Webhook processed successfully"
}
```

---

## 📊 Error Codes

| Code | Description |
|------|-------------|
| `AUTHENTICATION_FAILED` | Invalid or missing authentication token |
| `PERMISSION_DENIED` | User doesn't have permission for this action |
| `VALIDATION_ERROR` | Request validation failed |
| `RESOURCE_NOT_FOUND` | Requested resource not found |
| `CAMPAIGN_ALREADY_RUNNING` | Campaign is already running |
| `INSUFFICIENT_CREDITS` | User doesn't have enough credits |
| `PROXY_UNAVAILABLE` | No available proxies |
| `AUTOMATION_SERVICE_ERROR` | Automation service error |
| `RATE_LIMIT_EXCEEDED` | Rate limit exceeded |

---

## 📋 Rate Limiting

API endpoints are rate-limited to prevent abuse:

- **Authentication endpoints**: 5 requests per minute
- **Campaign endpoints**: 60 requests per minute
- **Analytics endpoints**: 30 requests per minute
- **Webhook endpoints**: 100 requests per minute

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 45
X-RateLimit-Reset: 1640995200
```

---

## 🔒 Security

### CORS
CORS is configured to allow requests from authorized domains only.

### Input Validation
All inputs are validated using Laravel's validation system.

### Data Encryption
Sensitive data (passwords, API keys) are encrypted using Laravel's encryption.

### Audit Logging
All API requests are logged for security and debugging purposes.

---

## 📚 SDKs and Libraries

### PHP SDK
```php
composer require backlinkforge/php-sdk
```

### JavaScript SDK
```bash
npm install @backlinkforge/js-sdk
```

### Python SDK
```bash
pip install backlinkforge-python
```

---

## 📞 Support

For API support and questions:
- **Email**: api-support@backlinkforge.com
- **Documentation**: https://docs.backlinkforge.com/api
- **Status Page**: https://status.backlinkforge.com

---

**Last Updated**: January 2024  
**Version**: 1.0.0  
**Status**: Active 