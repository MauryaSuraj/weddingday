# Secure Full-Stack Application
## Laravel 12 API + Angular 21 Frontend | Production-Ready | OWASP Compliant | SEO Optimized

### 🎯 Status: ✅ Production Ready

---

## 📋 Quick Navigation

- **[Architecture Overview](./ARCHITECTURE.md)** - Complete system design
- **[Project Structure](./PROJECT_STRUCTURE.md)** - Folder layout and organization
- **[Security Audit](./SECURITY_AUDIT.md)** - OWASP compliance matrix
- **[Deployment Guide](./DEPLOYMENT.md)** - Docker setup and operations

---

## 🚀 Quick Start

### Prerequisites
```bash
Node.js 18+
PHP 8.3+
Docker & Docker Compose
PostgreSQL 15 (or Docker)
Redis 7 (or Docker)
```

### Local Development Setup

**Backend**:
```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

**Frontend**:
```bash
cd frontend
npm install
npm start
```

**Docker** (Recommended):
```bash
cp .env.docker.example .env.docker
docker-compose up -d
```

### Access Application
- **Frontend**: http://localhost:4200
- **API**: http://localhost:8000/api/v1
- **Admin**: http://localhost:8000 (N/A - API only)

---

## 🔐 Security Features

✅ **Authentication & Authorization**
- Laravel Sanctum with HttpOnly, Secure, SameSite=Strict cookies
- Role-Based Access Control (RBAC)
- Policy-based authorization

✅ **Input Validation & Injection Prevention**
- FormRequest validation on all endpoints
- Eloquent ORM prevents SQL injection
- Protection against XSS, CSRF, command injection

✅ **API Security**
- Rate limiting (throttle middleware)
- CORS restricted to frontend domain
- Proper HTTP status codes
- No sensitive data in responses

✅ **Data Protection**
- HTTPS/TLS 1.2+ enforced
- Sensitive fields encrypted
- Secure logging (no secrets)
- UUID user IDs (prevents enumeration)

✅ **Security Headers**
- Content-Security-Policy
- Strict-Transport-Security
- X-Frame-Options: DENY
- X-Content-Type-Options: nosniff
- Referrer-Policy

---

## 📱 SEO Features

✅ **Server-Side Rendering**
- Angular Universal for pre-rendering
- Dynamic meta tags
- Optimized initial load

✅ **Meta Tags & Open Graph**
- Unique title and descriptions
- Open Graph tags
- Twitter Card tags
- Structured data (JSON-LD)

✅ **Performance**
- LCP < 2.5s (Largest Contentful Paint)
- Image optimization
- Gzip compression
- Lazy loading

✅ **Sitemaps & Robots**
- sitemap.xml generation
- robots.txt configuration
- Canonical URLs
- Breadcrumb schema

---

## 🏗️ Architecture Overview

```
┌─────────────────────────────────────────┐
│     Client (Angular 21 - SPA/SSR)      │
│  - Standalone Components                │
│  - Route Guards & Interceptors          │
│  - HTTPS + Secure Cookies               │
└────────────────┬────────────────────────┘
                 │ HTTPS
      ┌──────────▼──────────┐
      │  Nginx Reverse Proxy │
      │  (TLS Termination)   │
      └──────────┬───────────┘
                 │
┌────────────────▼──────────────────┐
│  Backend (Laravel 12 API)          │
│  - Sanctum Authentication          │
│  - RBAC Authorization              │
│  - Input Validation                │
│  - Security Headers                │
└────────────────┬──────────────────┘
       ┌─────────┼─────────┐
       │         │         │
   PostgreSQL  Redis    Cache
```

---

## 📦 Technology Stack

**Backend:**
- PHP 8.3+
- Laravel 12 Framework
- Laravel Sanctum Authentication
- PostgreSQL Database
- Redis Cache & Queue
- Eloquent ORM

**Frontend:**
- Angular 21
- Standalone Components
- Angular Universal (SSR)
- TypeScript (Strict Mode)
- RxJS

**DevOps:**
- Docker & Docker Compose
- Nginx Web Server
- PostgreSQL 15
- Redis 7
- Let's Encrypt SSL

---

## 🔑 Key Endpoints

### Authentication
```
POST   /api/v1/auth/login         - User login
POST   /api/v1/auth/register      - User registration
POST   /api/v1/auth/logout        - Logout user
POST   /api/v1/auth/refresh       - Refresh token
GET    /api/v1/auth/me            - Get current user
```

### Users (Protected)
```
GET    /api/v1/users              - List users (admin only)
GET    /api/v1/users/{id}         - Get user
PUT    /api/v1/users/{id}         - Update user
DELETE /api/v1/users/{id}         - Delete user (admin only)
```

### Health Check
```
GET    /api/v1/health             - System health
```

---

## 🧪 Testing

### Backend Tests
```bash
# Run PHPUnit tests
php artisan test

# Run with coverage
php artisan test --coverage
```

### Frontend Tests
```bash
# Run Karma tests
npm run test

# Run with coverage
npm run test:coverage
```

### Security Tests
```bash
# OWASP Dependency Check
npm audit
composer audit

# OWASP ZAP Scan
docker run -t owasp/zap2docker-stable zap-baseline.py ...
```

---

## 📊 File Structure

### Backend
```
backend/
├── app/
│   ├── Models/
│   ├── Http/Controllers/
│   ├── Http/Requests/
│   ├── Http/Middleware/
│   ├── Policies/
│   └── Services/
├── config/
├── database/
├── routes/
└── docker/
```

### Frontend
```
frontend/
├── src/
│   ├── app/
│   │   ├── core/
│   │   ├── features/
│   │   ├── shared/
│   │   └── app.routes.ts
│   ├── assets/
│   ├── environments/
│   └── index.html
└── docker/
```

---

## 🔄 Authentication Flow

### Login
```
1. User → POST /api/v1/auth/login
2. Backend validates credentials
3. Backend generates Sanctum token
4. Backend sets HttpOnly cookie
5. Backend returns user data
6. Frontend stores user in memory
7. Route guard allows access
```

### Refresh
```
1. Frontend detects token near expiration
2. Frontend → POST /api/v1/auth/refresh
3. Backend validates token
4. Backend issues new token
5. Backend extends cookie expiration
6. Frontend continues without interruption
```

### Logout
```
1. User → POST /api/v1/auth/logout
2. Backend revokes all tokens
3. Backend deletes cookie
4. Frontend clears user state
5. Frontend redirects to login
```

---

## 🚀 Deployment

### Docker Deployment
```bash
# Copy environment
cp .env.docker.example .env.docker

# Configure environment
nano .env.docker

# Build and start
docker-compose up -d

# Run migrations
docker-compose exec app php artisan migrate --seed
```

### Production Checklist
- [ ] SSL certificate installed (Let's Encrypt)
- [ ] Environment variables configured
- [ ] Database backed up
- [ ] Redis configured
- [ ] Security headers verified
- [ ] Rate limiting tested
- [ ] Logging configured
- [ ] Monitoring activated
- [ ] Backup automated

---

## 📚 Documentation

| Document | Purpose |
|----------|---------|
| [ARCHITECTURE.md](./ARCHITECTURE.md) | Complete system architecture and design |
| [PROJECT_STRUCTURE.md](./PROJECT_STRUCTURE.md) | Folder organization and file layout |
| [SECURITY_AUDIT.md](./SECURITY_AUDIT.md) | OWASP Top 10 compliance and security testing |
| [DEPLOYMENT.md](./DEPLOYMENT.md) | Docker setup and operations guide |

---

## 🐛 Common Issues

### Database Connection Error
```bash
# Check database is running
docker-compose logs db

# Restart database
docker-compose restart db
```

### Frontend Not Loading
```bash
# Check frontend logs
docker-compose logs frontend

# Rebuild frontend
docker-compose exec frontend npm run build:ssr
```

### SSL Certificate Issues
```bash
# Generate self-signed certificate (testing)
openssl req -x509 -newkey rsa:4096 -keyout key.pem -out cert.pem -days 365
```

---

## 🤝 Contributing

1. Create feature branch
2. Implement changes
3. Run tests
4. Submit pull request

---

## 📝 License

This project is provided as-is for authorized use only.

---

## 📞 Support

- **Security Issues**: security@yourdomain.com
- **Technical Support**: support@yourdomain.com
- **Documentation**: https://docs.yourdomain.com

---

## ✅ Quality Assurance Checklist

- [x] OWASP Top 10 Compliant
- [x] VAPT Ready
- [x] SEO Optimized
- [x] Docker Ready
- [x] Production Hardened
- [x] Security Headers Implemented
- [x] Rate Limiting Configured
- [x] Audit Logging Enabled
- [x] Error Handling Robust
- [x] Code Well-Documented

---

**Last Updated**: February 10, 2024  
**Version**: 1.0.0  
**Status**: ✅ Production Ready  
**Security Level**: ⭐⭐⭐⭐⭐ Maximum
