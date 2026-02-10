# Production-Ready Full-Stack Architecture
## Laravel 12 API + Angular 21 Frontend | Security-Hardened + SEO-Ready

---

## 📋 TABLE OF CONTENTS
1. [Overview](#overview)
2. [Security Architecture](#security-architecture)
3. [SEO Architecture](#seo-architecture)
4. [Authentication Flow](#authentication-flow)
5. [API Design](#api-design)
6. [Frontend Architecture](#frontend-architecture)
7. [Deployment](#deployment)
8. [OWASP Top 10 Compliance](#owasp-top-10-compliance)

---

## 🏗️ OVERVIEW

### Technology Stack
- **Backend**: Laravel 12 (PHP 8.3+) - API-only
- **Frontend**: Angular 21 (Standalone Components)
- **Authentication**: Laravel Sanctum (Cookie-based, HttpOnly)
- **Protocol**: HTTPS/REST
- **Deployment**: Docker + Nginx
- **Database**: PostgreSQL/MySQL (recommended: PostgreSQL)
- **Caching**: Redis
- **Queue**: Redis Queue/Beanstalkd

### Architecture Pattern
```
┌─────────────────────────────────────────────────────────┐
│                   CLIENT (Angular 21)                    │
│  - Standalone Components                                │
│  - Strict TypeScript Mode                               │
│  - Route Guards & Interceptors                          │
│  - Angular Universal (SSR for SEO)                      │
└──────────────────┬──────────────────────────────────────┘
                   │ HTTPS + Cookies (HttpOnly, Secure)
         ┌─────────▼────────────┐
         │  Nginx Reverse Proxy │
         │  (TLS Termination)   │
         └─────────┬────────────┘
                   │
┌──────────────────▼──────────────────────────────────────┐
│              BACKEND (Laravel 12 API)                    │
│  - API-only endpoints                                   │
│  - Sanctum authentication                               │
│  - RBAC Authorization                                   │
│  - Input validation (FormRequest)                       │
│  - Security headers                                     │
└──────────────────┬──────────────────────────────────────┘
                   │
       ┌───────────┼───────────┐
       │           │           │
    PostgreSQL   Redis       Cache
```

---

## 🔒 SECURITY ARCHITECTURE

### 1. AUTHENTICATION & AUTHORIZATION

#### Laravel Sanctum Configuration
```
- Token-based + Cookie-based authentication
- HttpOnly cookies (no JavaScript access)
- Secure & SameSite=Strict flags set
- Token expiration: 24 hours (configurable)
- Automatic token rotation on refresh
- RBAC with Roles & Permissions model
- Policy-based authorization per resource
```

#### User Roles
- `admin`: Full system access
- `manager`: Department/team management
- `user`: Own resource access only
- `guest`: Public endpoints only

#### Authorization Pattern
```
Policy → Controller → Service → Database
           ↓
      Audit Log
```

### 2. INPUT VALIDATION & INJECTION PREVENTION

#### Protection Mechanisms
- **SQL Injection**: Eloquent ORM only (parameterized queries)
- **XSS**: Output escaping + CSP headers
- **Command Injection**: No shell_exec/system calls
- **Path Traversal**: File operations validated
- **CSRF**: Laravel middleware enabled
- **CQRS**: Separation of concerns

#### FormRequest Validation
```php
// Every input is validated server-side
// Validate file types, sizes, MIME types
// UUID usage instead of predictable IDs
// Custom validation rules for business logic
```

### 3. CSRF & SESSION SECURITY

#### CSRF Protection
- Middleware: `\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken`
- CSRF token in request headers (auto-attached by Angular interceptor)
- No CSRF routes exclusions in production
- Token regenerated on login/logout

#### Session Management
- HttpOnly cookies prevent JavaScript access
- SameSite=Strict prevents CSRF attacks
- Secure flag for HTTPS only
- Path isolation
- Logout invalidates all sessions

### 4. CORS & SECURITY HEADERS

#### CORS Policy
```
Allowed Origins: https://yourdomain.com (frontend only)
Methods: GET, POST, PUT, DELETE, OPTIONS
Headers: Content-Type, Authorization, X-CSRF-TOKEN
Credentials: Include (for cookies)
Max-Age: 3600 seconds
```

#### Security Headers (Set by Middleware)
```
Content-Security-Policy: 
  default-src 'self'; 
  script-src 'self' 'nonce-{random}'; 
  style-src 'self' 'unsafe-inline'; 
  img-src 'self' data: https:; 
  connect-src 'self' https://api.yourdomain.com

X-Frame-Options: DENY
X-Content-Type-Options: nosniff
Strict-Transport-Security: max-age=31536000; includeSubDomains
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

### 5. API SECURITY

#### Rate Limiting
```
- 60 requests/minute per authenticated user
- 15 requests/minute per IP (unauthenticated)
- Cached throttle using Redis
- Returns 429 (Too Many Requests) when exceeded
```

#### API Versioning
```
/api/v1/users     - Version 1 endpoints
/api/v1/products  - Stable, production-ready
```

#### Response Format (Consistent)
```json
{
  "success": true,
  "data": { ... },
  "message": "Optional message",
  "timestamp": "ISO-8601"
}
```

Error Response:
```json
{
  "success": false,
  "error": {
    "code": "RESOURCE_NOT_FOUND",
    "message": "User not found",
    "details": null
  },
  "timestamp": "ISO-8601"
}
```

### 6. FILE UPLOAD SECURITY

#### Validation Layer
```php
// Validate MIME type
// Validate file extension (whitelist approach)
// Validate file size (max 10MB)
// Block executable files (.exe, .sh, .bat, .php)
// Rename file with UUID + timestamp
// Store in non-public directory (/storage/app/uploads)
// No direct execution capability
```

#### Storage Architecture
```
storage/
├── app/
│   └── uploads/     ← Files stored here (non-public)
├── logs/
└── framework/
public/
└── index.php        ← Only entry point
```

### 7. ERROR HANDLING & LOGGING

#### Production Configuration
```
APP_DEBUG=false              ← Stack traces hidden
APP_ENV=production           ← Production mode
LARAVEL_LOG_CHANNEL=single   ← Aggregated logging
LOG_LEVEL=warning            ← Only important events
```

#### Logging Strategy
```
- No sensitive data (passwords, tokens, emails) logged
- Unique request ID for tracing
- Audit log for user actions
- Security event logging
- Error aggregation tool (Sentry)
```

#### Custom Error Handler
```php
// Returns JSON error responses
// No stack traces exposed
// Security error codes instead of technical details
// Client-friendly messages
```

### 8. DATABASE SECURITY

#### Protection
```
- Parameterized queries (Eloquent ORM)
- Row-level security (Policy-based)
- Encryption for sensitive fields
- Regular automated backups
- Database user with minimal permissions
- Connection over SSL/TLS
```

#### Sensitive Data Encryption
```php
// Encrypted columns in model
protected $encrypted = ['ssn', 'credit_card', 'secret'];

// Automatic encryption/decryption
// Uses Laravel's encryption key (APP_KEY)
```

### 9. SECRETS MANAGEMENT

#### Environment Variables
```
.env file (NEVER committed)
- Database credentials
- API keys
- Encryption keys
- Third-party secrets

Production: Use environment variables or secrets manager
```

#### No Secrets in Code
```
✗ Hardcoded API keys
✗ Credentials in version control
✗ Secrets in logs
✓ Environment variables
✓ Secrets manager (AWS Secrets Manager, HashiCorp Vault)
```

---

## 📱 SEO ARCHITECTURE

### 1. SERVER-SIDE RENDERING (Angular Universal)

#### Setup
```
- Angular Universal pre-renders public routes
- Dynamic meta tags via HttpClient on server
- Initial state serialized to JSON
- Bootstrap transfer state to browser
- Post-render hydration for interactivity
```

#### Performance Optimizations
```
- Lighthouse Core Web Vitals optimized
- LCP (Largest Contentful Paint) < 2.5s
- FID (First Input Delay) < 100ms
- CLS (Cumulative Layout Shift) < 0.1
- Gzip compression enabled
- Image optimization (next-gen formats)
```

### 2. META TAGS & OPEN GRAPH

#### Home Page (/index)
```html
<title>Company Name - Professional Services</title>
<meta name="description" content="...">
<meta name="keywords" content="...">
<meta property="og:title" content="...">
<meta property="og:description" content="...">
<meta property="og:image" content="...">
<meta property="og:url" content="https://yourdomain.com">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="...">
<meta name="twitter:description" content="...">
<meta name="twitter:image" content="...">
<link rel="canonical" href="https://yourdomain.com">
```

#### About Page (/about)
```html
<title>About Us - Company Name</title>
<meta name="description" content="Learn about our company...">
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Organization",
  "name": "Company Name",
  "description": "...",
  "url": "https://yourdomain.com",
  "logo": "https://yourdomain.com/logo.png",
  "sameAs": ["https://facebook.com/...", "https://twitter.com/..."],
  "contactPoint": {
    "@type": "ContactPoint",
    "contactType": "Customer Service",
    "email": "contact@yourdomain.com",
    "telephone": "+1-xxx-xxx-xxxx"
  }
}
</script>
```

### 3. SITEMAP & ROBOTS.TXT

#### robots.txt
```
User-agent: *
Allow: /
Allow: /about
Allow: /contact
Disallow: /api/
Disallow: /admin/
Disallow: /login
Sitemap: https://yourdomain.com/sitemap.xml
```

#### sitemap.xml
```xml
<urlset>
  <url>
    <loc>https://yourdomain.com/</loc>
    <lastmod>2024-01-15</lastmod>
    <changefreq>weekly</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/about</loc>
    <lastmod>2024-01-15</lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>
```

### 4. STRUCTURED DATA

#### Schema.org JSON-LD
```
- Organization schema on home page
- ContactPoint with email/phone
- LocalBusiness schema (if applicable)
- BreadcrumbList for navigation
- Article schema for blog posts
```

### 5. URL STRUCTURE

#### Semantic URLs
```
✓ /about               ← Readable
✓ /contact             ← Self-explanatory
✗ /page?id=1           ← Parameter-based
✗ /1234567             ← ID-based
```

#### Canonicalization
```
- Trailing slashes: Enforce consistency
- HTTP vs HTTPS: Redirect to HTTPS
- www vs non-www: Redirect uniformly
- Canonical link tags on duplicate content
```

### 6. SEO Performance

#### Core Web Vitals
```
LCP: Optimize above-fold content
FID: Minimize main thread blocking
CLS: Prevent layout shifts
TTL: Time to Lexically parseable content
```

#### Image Optimization
```
- WebP format with fallbacks
- Responsive images (srcset)
- Lazy loading for below-fold
- Appropriate alt text
```

---

## 🔐 AUTHENTICATION FLOW

### Login Flow
```
1. User submits credentials (email, password)
2. Frontend POST /api/v1/auth/login
3. Backend validates (rate limit, credentials)
4. Hash password comparison (bcrypt)
5. Generate Sanctum token
6. Set httpOnly cookie (sanctum_api_token)
7. Return user data (no password/token)
8. Frontend stores user state (in-memory)
9. Redirect to dashboard
```

### Refresh/Keep-Alive
```
1. Token expires in 24 hours
2. Frontend automatically calls /api/v1/auth/refresh
3. Backend validates existing token
4. Issue new token
5. Extend cookie expiration
6. Return success
```

### Logout Flow
```
1. User clicks logout
2. Frontend POST /api/v1/auth/logout
3. Backend revokes all Sanctum tokens
4. Delete httpOnly cookie
5. Clear user state (frontend)
6. Redirect to login
```

### Protected Request Flow
```
1. Frontend Angular@21 interceptor checks cookie
2. Add CSRF token to X-CSRF-TOKEN header
3. POST /api/v1/users with Authorization header
4. Backend middleware verifies token
5. Return protected resource
```

---

## 📡 API DESIGN

### Endpoint Structure
```
GET    /api/v1/users              → List (paginated, filtered)
GET    /api/v1/users/{id}         → Show single
POST   /api/v1/users              → Create
PUT    /api/v1/users/{id}         → Update
DELETE /api/v1/users/{id}         → Delete

All endpoints require:
- Authentication (except public endpoints)
- Authorization (via Policies)
- Input validation (via FormRequest)
- Rate limiting
```

### Response Pagination
```json
{
  "success": true,
  "data": [
    { "id": "uuid", "name": "...", "email": "..." }
  ],
  "pagination": {
    "total": 100,
    "per_page": 15,
    "current_page": 1,
    "last_page": 7,
    "from": 1,
    "to": 15
  },
  "timestamp": "2024-02-10T10:30:00Z"
}
```

---

## 🎯 FRONTEND ARCHITECTURE

### Folder Structure
```
src/
├── app/
│   ├── core/
│   │   ├── auth/
│   │   │   ├── auth.service.ts
│   │   │   ├── auth.guard.ts
│   │   │   └── auth.interceptor.ts
│   │   ├── services/
│   │   │   ├── http.service.ts
│   │   │   └── api.service.ts
│   │   └── models/
│   │       ├── user.model.ts
│   │       └── auth.model.ts
│   ├── features/
│   │   ├── home/
│   │   ├── about/
│   │   ├── auth/
│   │   └── dashboard/
│   ├── shared/
│   │   ├── components/
│   │   ├── directives/
│   │   └── pipes/
│   └── app.routes.ts
├── assets/
├── environments/
│   ├── environment.ts
│   ├── environment.prod.ts
│   └── environment.dev.ts
└── styles/
    └── global.scss
```

### Route Protection
```typescript
// Protected routes using AuthGuard
const routes: Routes = [
  {
    path: '',
    component: HomeComponent
  },
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [AuthGuard],
    canDeactivate: [UnsavedChangesGuard]
  }
];
```

### HTTP Interceptor
```typescript
// Auto-attach CSRF token
// Handle 401/403 responses
// Global error handling
// Request timeout
```

---

## 🐳 DEPLOYMENT

### Docker Compose
```yaml
services:
  web:
    image: nginx:latest
    ports:
      - "443:443"
    volumes:
      - ./nginx.conf:/etc/nginx/nginx.conf
      - ./ssl:/etc/nginx/ssl
    depends_on:
      - app

  app:
    build: ./backend
    environment:
      - APP_DEBUG=false
      - APP_ENV=production
      - DB_HOST=db
    depends_on:
      - db
      - redis

  db:
    image: postgres:15
    environment:
      - POSTGRES_DB=${DB_DATABASE}
      - POSTGRES_PASSWORD=${DB_PASSWORD}
    volumes:
      - db_data:/var/lib/postgresql/data

  redis:
    image: redis:7
```

### SSL/TLS
```
- Nginx handles TLS termination
- Let's Encrypt certificates (automated renewal)
- HSTS header enabled
- Only TLS 1.2+ supported
```

---

## ⛔ OWASP TOP 10 COMPLIANCE

| # | Vulnerability | Prevention |
|---|---|---|
| 1 | Broken Access Control | RBAC Policies, AuthGuard on routes, Authorization middleware |
| 2 | Cryptographic Failures | APP_KEY encryption, HTTPS required, bcrypt hashing |
| 3 | Injection | Eloquent ORM, FormRequest validation, parameterized queries |
| 4 | Insecure Design | Security-by-default, secure configuration, OWASP considerations |
| 5 | Security Misconfiguration | APP_DEBUG=false, no directory listing, secure headers, .env |
| 6 | Vulnerable Components | Composer/npm lock files, security advisories, regular updates |
| 7 | Authentication Failure | Sanctum tokens, rate limiting, secure cookies, token rotation |
| 8 | Data Integrity Failures | CSRF protection, input validation, signed requests |
| 9 | Logging & Monitoring | Audit logs, error tracking (Sentry), security event logs |
| 10 | SSRF | Input validation, whitelist allowed URLs, no external fetches |

---

## ✅ SECURITY CHECKLIST

### Pre-Production
- [ ] Database credentials in .env (not committed)
- [ ] APP_DEBUG=false in production
- [ ] APP_ENV=production set
- [ ] HTTPS/SSL certificate configured
- [ ] CORS origins restricted to frontend domain only
- [ ] Rate limiting enabled on all endpoints
- [ ] Audit logging configured
- [ ] Error tracking (Sentry) integrated
- [ ] Database backups automated
- [ ] Admin routes protected
- [ ] File uploads directory outside public
- [ ] Sensitive fields encrypted in database
- [ ] Security headers middleware enabled
- [ ] CSRF protection enabled
- [ ] All inputs validated server-side
- [ ] No console logs in production
- [ ] CSP headers configured
- [ ] X-Frame-Options set
- [ ] X-Content-Type-Options set
- [ ] Cookies have HttpOnly, Secure, SameSite flags
- [ ] Dependencies vulnerabilities scanned
- [ ] YAML/JSON config files validated
- [ ] SQL queries use parameterization only
- [ ] Third-party APIs use HTTPS
- [ ] Rate limits configured appropriately
- [ ] Admin accounts strong passwords
- [ ] Two-factor authentication available (optional)
- [ ] Security headers tested (securityheaders.com)

### Post-Production
- [ ] Monitor error logs daily
- [ ] Review security event logs weekly
- [ ] Run OWASP ZAP security scan monthly
- [ ] Perform VAPT annually
- [ ] Update dependencies monthly
- [ ] Check for security advisories
- [ ] Monitor rate limiting effectiveness
- [ ] Audit user access logs
- [ ] Test disaster recovery plan
- [ ] Review and update security policies

---

## 📚 REFERENCES

- **OWASP Top 10**: https://owasp.org/www-project-top-ten/
- **Laravel Security**: https://laravel.com/docs/11/security
- **Angular Security**: https://angular.io/guide/security
- **NIST Cybersecurity**: https://nvlpubs.nist.gov/
- **CWE Top 25**: https://cwe.mitre.org/top25/
- **Secure SDLC**: https://cheatsheetseries.owasp.org/

---

**Last Updated**: February 10, 2024  
**Status**: Production-Ready  
**Version**: 1.0.0  
**Security Level**: Maximum
