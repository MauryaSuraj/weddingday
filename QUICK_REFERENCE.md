# Quick Reference Guide

## 🚀 Getting Started in 5 Minutes

### Option 1: Local Development

```bash
# Backend
cd backend
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve

# Frontend (separate terminal)
cd frontend
npm install
npm start
```

**Access**: Frontend at http://localhost:4200, API at http://localhost:8000

### Option 2: Docker

```bash
cp .env.docker.example .env.docker
docker-compose up -d
docker-compose exec app php artisan migrate --seed
```

**Access**: http://localhost (with Docker)

---

## 🔑 Default Credentials (Development Only)

**Admin Account** (Seeded):
- Email: admin@example.com
- Password: Password123!

**Test Accounts** (Seeded):
- User account for testing
- Multiple test users created via seeder

⚠️ **IMPORTANT**: Change all credentials in production!

---

## 📡 API Quick Reference

### Login
```bash
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "Password123!"
}

Response:
{
  "success": true,
  "data": {
    "user": { ... },
    "token": "..."
  }
}
```

### Get Current User
```bash
GET /api/v1/auth/me
Authorization: Bearer <token>
```

### Create User (Admin Only)
```bash
POST /api/v1/users
Authorization: Bearer <token>
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "SecurePassword123!"
}
```

### Logout
```bash
POST /api/v1/auth/logout
Authorization: Bearer <token>
```

---

## 🛡️ Security Best Practices

### For Backend Developers
1. **Always validate input** via FormRequest
2. **Never hardcode secrets** - use environment variables
3. **Use Eloquent ORM** - never raw SQL
4. **Log security events** via audit log
5. **Implement policies** for authorization
6. **Test authorization** on all endpoints
7. **Never expose sensitive data** in responses
8. **Use HTTPS only** in production

### For Frontend Developers
1. **Always use AuthGuard** on protected routes
2. **Don't store tokens** in localStorage
3. **Let interceptors handle** CSRF tokens
4. **Implement 401/403 handling** globally
5. **Sanitize user input** before display
6. **Use Angular's security** features
7. **Don't trust frontend validation** alone
8. **Keep sensitive logic server-side**

---

## 🗄️ Database Models Quick Reference

### User Model
```php
$user = User::find($id);
$user->roles;           // Get user's roles
$user->hasRole('admin'); // Check role
$user->logAction('action.name'); // Log action
```

### Role Model
```php
$role = Role::where('name', 'admin')->first();
$role->users;              // Get users with role
$role->grantPermission(); // Add permission
$role->permissions;        // Get permissions
```

### Audit Log
```php
AuditLog::where('user_id', $userId)
    ->where('created_at', '>=', now()->subDays(7))
    ->get();
```

---

## 📦 Adding a New API Endpoint

### 1. Create Controller
```php
// app/Http/Controllers/Api/V1/ProductController.php
class ProductController extends Controller {
    public function index(): JsonResponse {
        // Implement
    }
}
```

### 2. Create Form Request
```php
// app/Http/Requests/ProductStoreRequest.php
public function rules() {
    return [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
    ];
}
```

### 3. Create Policy
```php
// app/Policies/ProductPolicy.php
public function create(User $user): bool {
    return $user->isAdmin();
}
```

### 4. Register Route
```php
// routes/api.php
Route::apiResource('products', ProductController::class)
    ->middleware('auth:sanctum');
```

---

## 🎯 Adding a New Frontend Route

### 1. Create Component
```typescript
// src/app/features/products/products.component.ts
@Component({
  selector: 'app-products',
  standalone: true,
  template: `...`
})
export class ProductsComponent implements OnInit {
  constructor(private seoService: SeoService) {}
  
  ngOnInit() {
    this.seoService.updateSEO({
      title: 'Products',
      description: '...'
    });
  }
}
```

### 2. Add Route
```typescript
// src/app/app.routes.ts
{
  path: 'products',
  loadComponent: () => import('./features/products/products.component')
    .then(m => m.ProductsComponent),
  canActivate: [AuthGuard],
  data: { title: 'Products' }
}
```

---

## 🧪 Testing Quick Reference

### Run All Tests
```bash
# Backend
php artisan test

# Frontend
npm run test
```

### Test Specific File
```bash
# Backend
php artisan test tests/Feature/AuthTest.php

# Frontend
npm run test -- --include='**/auth.service.spec.ts'
```

### Test with Coverage
```bash
# Backend
php artisan test --coverage

# Frontend
npm run test:coverage
```

---

## 🔐 Common Security Checks

### Verify Rate Limiting
```bash
for i in {1..10}; do
  curl -X POST http://localhost:8000/api/v1/auth/login \
    -H "Content-Type: application/json" \
    -d '{"email":"test@test.com","password":"wrong"}'
done

# Should return 429 after 5 attempts
```

### Test CORS
```bash
curl -H "Origin: https://attacker.com" \
  -H "Access-Control-Request-Method: POST" \
  -X OPTIONS http://localhost:8000/api/v1/users

# Should return no CORS headers
```

### Check Security Headers
```bash
curl -I http://localhost:8000 | grep -E 'Content-Security-Policy|X-Frame-Options'
```

---

## 📝 Naming Conventions

### Database Tables
- Lowercase, plural: `users`, `products`, `orders`
- Timestamps: `created_at`, `updated_at`
- Foreign keys: `user_id`, `product_id`

### Models
- Singular, PascalCase: `User`, `Product`

### Controllers
- PascalCase + Controller: `UserController`, `ProductController`

### Routes
- Lowercase, snake_case: `/api/v1/products`, `/api/v1/user-settings`

### Components
- Lowercase, kebab-case: `product-list.component.ts`

### Services
- PascalCase + Service: `AuthService`, `ProductService`

---

## 🐛 Debugging Tips

### Backend
```php
// Log with context
Log::info('User login', [
    'email' => $email,
    'ip' => request()->ip(),
    'user_agent' => request()->userAgent(),
]);

// DB query logging
DB::enableQueryLog();
// ... queries ...
dd(DB::getQueryLog());
```

### Frontend
```typescript
// Environment-aware logging
if (environment.debug) {
  console.log('Debug info');
}

// RxJS debugging
source$.pipe(
  tap(value => console.log('Value:', value))
).subscribe(...);
```

---

## 📋 Pre-Deployment Checklist

- [ ] All tests passing
- [ ] No console errors/warnings
- [ ] APP_DEBUG=false set
- [ ] APP_ENV=production set
- [ ] Database backups verified
- [ ] SSL certificate configured
- [ ] Environment variables reviewed
- [ ] Dependencies up to date
- [ ] Security headers verified
- [ ] Rate limits tested
- [ ] Logging configured
- [ ] Error tracking (Sentry) setup

---

## 🔗 Useful Commands

### Backend
```bash
php artisan migrate            # Run migrations
php artisan migrate:rollback   # Rollback migrations
php artisan tinker             # Interactive shell
php artisan make:controller ProductController
php artisan make:model Product
php artisan make:request ProductRequest
php artisan make:migration create_products_table
php artisan db:seed            # Run seeders
```

### Frontend
```bash
ng generate component features/products
ng generate service core/services/product
ng build                       # Production build
ng build:ssr                   # SSR build
ng test                        # Run tests
ng lint                        # Lint code
```

### Docker
```bash
docker-compose up              # Start services
docker-compose down            # Stop services
docker-compose logs -f         # View logs
docker-compose exec app bash   # Execute in container
docke-compose restart          # Restart services
```

---

## 📚 Further Reading

- **[Full Architecture](./ARCHITECTURE.md)** - Complete system design
- **[Security Audit](./SECURITY_AUDIT.md)** - Testing procedures
- **[Deployment Guide](./DEPLOYMENT.md)** - Production setup
- **[Laravel Docs](https://laravel.com/docs)** - Framework documentation
- **[Angular Docs](https://angular.io/docs)** - Framework documentation
- **[OWASP](https://owasp.org)** - Security best practices

---

## ❓ Frequently Asked Questions

**Q: How do I add a new role?**
```php
Role::create(['name' => 'manager', 'description' => 'Manager role']);
```

**Q: How do I assign a role to a user?**
```php
$user->assignRole('manager');
```

**Q: How do I check user permissions in frontend?**
```typescript
if (this.authService.hasRole('admin')) {
  // Show admin content
}
```

**Q: How do I handle 401 responses?**
```typescript
// Handled automatically by AuthInterceptor
// User is logged out and redirected to login
```

**Q: Where are uploaded files stored?**
```
/storage/app/uploads/   # Secure location, outside public
```

**Q: How do I extend the session timeout?**
Edit `SANCTUM_TOKEN_EXPIRATION` in .env (minutes)

---

**Last Updated**: February 10, 2024  
**For Latest Docs**: See [README.md](./README.md)
