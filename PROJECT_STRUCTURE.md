# PROJECT FOLDER STRUCTURE

## Backend (Laravel 12 - API Only)

```
backend/
├── app/
│   ├── Models/
│   │   ├── User.php                    # User model with roles
│   │   ├── Role.php                    # Role model
│   │   ├── Permission.php              # Permission model
│   │   └── Traits/
│   │       ├── HasRoles.php            # Role assignment trait
│   │       └── UserHasUUIDs.php        # UUID primary key
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       └── V1/
│   │   │           ├── AuthController.php          # Login, logout, refresh
│   │   │           ├── UserController.php          # CRUD operations
│   │   │           └── ProfileController.php       # User profile
│   │   ├── Requests/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginRequest.php                # Login validation
│   │   │   │   ├── RegisterRequest.php             # Registration validation
│   │   │   │   └── RefreshTokenRequest.php         # Token refresh
│   │   │   ├── UserStoreRequest.php                # User creation
│   │   │   ├── UserUpdateRequest.php               # User update
│   │   │   └── ProfileUpdateRequest.php            # Profile update
│   │   ├── Middleware/
│   │   │   ├── ApiResponseFormatter.php            # Unified API responses
│   │   │   ├── SecurityHeaders.php                 # CSP, HSTS, etc.
│   │   │   ├── CheckApiVersion.php                 # API versioning
│   │   │   ├── throttle.php                        # Rate limiting config
│   │   │   └── TrustProxies.php                    # Nginx trust headers
│   │   └── Resources/
│   │       ├── UserResource.php                    # User serialization
│   │       ├── AuthResource.php                    # Auth response
│   │       └── ErrorResource.php                   # Error response
│   ├── Policies/
│   │   ├── UserPolicy.php                          # User authorization
│   │   └── ProfilePolicy.php                       # Profile authorization
│   ├── Services/
│   │   ├── AuthService.php                         # Auth business logic
│   │   ├── UserService.php                         # User management
│   │   ├── TokenService.php                        # Token generation
│   │   └── AuditLogger.php                         # Action logging
│   ├── Exceptions/
│   │   ├── ApiException.php                        # Base API exception
│   │   ├── ResourceNotFoundException.php           # 404 handling
│   │   └── UnauthorizedActionException.php         # 403 handling
│   └── Console/
│       └── Commands/
│           ├── CreateAdminUser.php                 # Create first admin
│           └── GenerateSitemap.php                 # SEO sitemap
├── config/
│   ├── app.php                                     # App config
│   ├── database.php                                # DB config
│   ├── sanctum.php                                 # Sanctum auth config
│   ├── cors.php                                    # CORS policy
│   ├── cache.php                                   # Cache driver
│   ├── session.php                                 # Session config
│   ├── logging.php                                 # Logging config
│   └── security.php                                # Custom security config
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000000_create_users_table.php
│   │   ├── 2024_01_01_000001_create_roles_table.php
│   │   ├── 2024_01_01_000002_create_permissions_table.php
│   │   ├── 2024_01_01_000003_create_role_user_table.php
│   │   ├── 2024_01_01_000004_create_audit_logs_table.php
│   │   └── 2024_01_01_000005_create_personal_access_tokens_table.php
│   ├── seeders/
│   │   ├── DatabaseSeeder.php
│   │   ├── RoleSeeder.php
│   │   └── PermissionSeeder.php
│   └── factories/
│       └── UserFactory.php
├── routes/
│   ├── api.php                                     # API routes /api/v1
│   └── web.php                                     # Web routes (if needed)
├── resources/
│   └── views/
│       └── mail/                                   # Email templates
├── tests/
│   ├── Feature/
│   │   ├── AuthTest.php
│   │   └── UserTest.php
│   └── Unit/
│       ├── Services/
│       └── Models/
├── storage/
│   ├── app/
│   │   └── uploads/                                # User file uploads (non-public)
│   ├── logs/
│   │   └── laravel.log
│   └── framework/
├── bootstrap/
├── docker/
│   ├── Dockerfile                                  # PHP-FPM container
│   ├── nginx.conf                                  # Nginx config
│   └── php.ini                                     # PHP config
├── .github/
│   └── workflows/
│       ├── test.yml                                # PHPUnit tests
│       └── security-scan.yml                       # OWASP scanning
├── .env.example                                    # Environment template
├── artisan                                         # Artisan CLI
├── composer.json                                   # Dependencies
├── docker-compose.yml                              # Docker orchestration
├── nginx.conf                                      # Production nginx config
└── README.md                                       # Setup instructions
```

---

## Frontend (Angular 21 - Standalone Components)

```
frontend/
├── src/
│   ├── app/
│   │   ├── core/
│   │   │   ├── auth/
│   │   │   │   ├── auth.service.ts                 # Auth logic
│   │   │   │   ├── auth.guard.ts                   # Route protection
│   │   │   │   ├── auth.interceptor.ts             # HTTP interception
│   │   │   │   ├── models/
│   │   │   │   │   ├── auth.model.ts               # Auth interfaces
│   │   │   │   │   ├── user.model.ts               # User interfaces
│   │   │   │   │   └── login-response.model.ts     # API response types
│   │   │   │   └── storage/
│   │   │   │       └── auth.storage.ts             # SessionStorage ops
│   │   │   ├── services/
│   │   │   │   ├── http.service.ts                 # Base HTTP client
│   │   │   │   ├── api.service.ts                  # API methods
│   │   │   │   ├── seo.service.ts                  # Meta tags
│   │   │   │   └── error-handler.service.ts        # Error management
│   │   │   ├── interceptors/
│   │   │   │   ├── auth.interceptor.ts             # Add tokens
│   │   │   │   ├── error.interceptor.ts            # Error handling
│   │   │   │   └── logging.interceptor.ts          # Request logging
│   │   │   ├── guards/
│   │   │   │   ├── auth.guard.ts                   # Auth check
│   │   │   │   ├── role.guard.ts                   # Role check
│   │   │   │   └── unsaved-changes.guard.ts        # Form protection
│   │   │   └── models/
│   │   │       ├── api-response.model.ts           # API response wrapper
│   │   │       └── error.model.ts                  # Error types
│   │   ├── features/
│   │   │   ├── auth/
│   │   │   │   ├── login/
│   │   │   │   │   ├── login.component.ts
│   │   │   │   │   ├── login.component.html
│   │   │   │   │   ├── login.component.scss
│   │   │   │   │   └── login.component.spec.ts
│   │   │   │   ├── logout/
│   │   │   │   └── auth.routes.ts
│   │   │   ├── home/
│   │   │   │   ├── home.component.ts                # Home page component
│   │   │   │   ├── home.component.html              # SEO optimized
│   │   │   │   ├── home.component.scss
│   │   │   │   ├── home.component.spec.ts
│   │   │   │   └── home.routes.ts
│   │   │   ├── about/
│   │   │   │   ├── about.component.ts               # About page component
│   │   │   │   ├── about.component.html             # SEO optimized
│   │   │   │   ├── about.component.scss
│   │   │   │   ├── about.component.spec.ts
│   │   │   │   └── about.routes.ts
│   │   │   ├── dashboard/
│   │   │   │   ├── dashboard.component.ts           # Protected route
│   │   │   │   ├── dashboard.component.html
│   │   │   │   ├── dashboard.component.scss
│   │   │   │   ├── dashboard.component.spec.ts
│   │   │   │   └── dashboard.routes.ts
│   │   │   └── profile/
│   │   │       ├── profile.component.ts
│   │   │       ├── profile.component.html
│   │   │       ├── profile.component.scss
│   │   │       └── profile.component.spec.ts
│   │   ├── shared/
│   │   │   ├── components/
│   │   │   │   ├── header/
│   │   │   │   │   ├── header.component.ts
│   │   │   │   │   └── header.component.html
│   │   │   │   ├── footer/
│   │   │   │   │   ├── footer.component.ts
│   │   │   │   │   └── footer.component.html
│   │   │   │   ├── nav/
│   │   │   │   │   ├── nav.component.ts
│   │   │   │   │   └── nav.component.html
│   │   │   │   └── loading-spinner/
│   │   │   │       ├── loading-spinner.component.ts
│   │   │   │       └── loading-spinner.component.html
│   │   │   ├── directives/
│   │   │   │   ├── has-role.directive.ts            # Role-based display
│   │   │   │   └── highlight.directive.ts           # Highlight directive
│   │   │   ├── pipes/
│   │   │   │   └── safe-url.pipe.ts                 # Safe DomSanitizer
│   │   │   ├── validators/
│   │   │   │   ├── password.validator.ts            # Password strength
│   │   │   │   └── email.validator.ts               # Email format
│   │   │   └── constants/
│   │   │       ├── api-endpoints.ts                 # API URLs
│   │   │       └── messages.ts                      # UI messages
│   │   ├── app.routes.ts                            # Main routes
│   │   ├── app.config.ts                            # App config
│   │   └── app.component.ts                         # Root component
│   ├── assets/
│   │   ├── images/
│   │   │   ├── logo.svg
│   │   │   ├── favicon.ico
│   │   │   └── og-image.png                         # Open Graph image
│   │   ├── styles/
│   │   │   └── global.scss                          # Global styles
│   │   └── data/
│   │       └── company-data.json                    # SEO structured data
│   ├── environments/
│   │   ├── environment.ts                           # Dev environment
│   │   ├── environment.prod.ts                      # Prod environment
│   │   └── environment.dev.ts                       # Local dev
│   ├── styles/
│   │   ├── _variables.scss
│   │   ├── _mixins.scss
│   │   └── global.scss
│   ├── main.ts                                      # Bootstrap
│   ├── index.html                                   # SEO meta tags
│   └── main.server.ts                               # Angular Universal (SSR)
├── server.ts                                        # Express server (SSR)
├── angular.json                                     # Angular config
├── tsconfig.json                                    # TypeScript config
├── tsconfig.app.json                                # App TS config
├── karma.conf.js                                    # Test config
├── package.json                                     # Dependencies
├── package-lock.json                                # Lock file
├── .github/
│   └── workflows/
│       ├── build.yml                                # Build pipeline
│       └── deploy.yml                               # Deploy pipeline
├── docker.build.yml                                 # Frontend Docker config
├── Dockerfile                                       # Frontend container
├── nginx.conf                                       # Nginx config (SSR)
├── _prerender-routes.txt                            # Pre-render routes
└── README.md                                        # Setup instructions
```

---

## Key Security Features by Location

### Backend Security
- ✅ `config/sanctum.php` - Authentication config
- ✅ `config/cors.php` - CORS policy
- ✅ `config/security.php` - Custom security settings
- ✅ `app/Http/Middleware/SecurityHeaders.php` - Security headers
- ✅ `app/Http/Middleware/CheckApiVersion.php` - API versioning
- ✅ `app/Policies/*.php` - Authorization logic
- ✅ `app/Http/Requests/*.php` - Input validation
- ✅ `app/Services/AuditLogger.php` - Audit logging

### Frontend Security
- ✅ `core/auth/auth.guard.ts` - Route protection
- ✅ `core/auth/auth.interceptor.ts` - HTTP security
- ✅ `environments/environment.prod.ts` - Production config (debug off)
- ✅ `core/services/error-handler.service.ts` - Error handling
- ✅ `index.html` - CSP meta tags, SEO meta tags

### SEO Features
- ✅ `main.server.ts` - Angular Universal for SSR
- ✅ `server.ts` - Express server for pre-rendering
- ✅ `features/home/home.component.ts` - Meta tags
- ✅ `features/about/about.component.ts` - Structured data
- ✅ `index.html` - Base SEO tags
- ✅ `assets/data/company-data.json` - JSON-LD schema

---

## Installation & Setup

### Backend Setup
```bash
cd backend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

### Frontend Setup
```bash
cd frontend
npm install
npm run dev
```

### Docker Deployment
```bash
docker-compose up -d
# Access at https://yourdomain.com
```
