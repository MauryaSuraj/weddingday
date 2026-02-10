# Deployment and Operations Guide

## Quick Start

### Prerequisites
- Docker & Docker Compose
- Git
- SSL certificates (Let's Encrypt recommended)

### Installation

```bash
# Clone repository
git clone https://github.com/yourusername/secure-fullstack-app.git
cd secure-fullstack-app

# Copy environment variables
cp .env.docker.example .env.docker

# Edit environment variables
nano .env.docker

# Generate Laravel key
docker-compose run --rm app php artisan key:generate

# Run migrations
docker-compose run --rm app php artisan migrate --seed

# Start services
docker-compose up -d

# Verify services are running
docker-compose ps
```

### Access Application
- Frontend: https://yourdomain.com
- API: https://yourdomain.com/api/v1
- Backend: https://yourdomain.com:9000 (internal only)

---

## SSL/TLS Setup with Let's Encrypt

### Using Certbot

```bash
# Install certbot
sudo apt-get install certbot python3-certbot-nginx

# Generate certificate
sudo certbot certonly --standalone \
  -d yourdomain.com \
  -d www.yourdomain.com

# Copy certificates to Docker volume
sudo cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem ./ssl/cert.pem
sudo cp /etc/letsencrypt/live/yourdomain.com/privkey.pem ./ssl/key.pem

# Set permissions
sudo chmod 644 ./ssl/cert.pem
sudo chmod 640 ./ssl/key.pem

# Restart Nginx
docker-compose restart nginx
```

### Auto-renewal

```bash
# Create renewal script
sudo crontab -e

# Add to crontab
0 3 * * * certbot renew --quiet && cp /etc/letsencrypt/live/yourdomain.com/fullchain.pem /path/to/ssl/cert.pem && cp /etc/letsencrypt/live/yourdomain.com/privkey.pem /path/to/ssl/key.pem && docker-compose restart nginx
```

---

## Database Management

### Backup Database

```bash
docker-compose exec db pg_dump -U app_user secure_app_db > backup.sql
```

### Restore Database

```bash
cat backup.sql | docker-compose exec -T db psql -U app_user secure_app_db
```

### Migration

```bash
docker-compose exec app php artisan migrate
docker-compose exec app php artisan migrate --seed
```

---

## Monitoring & Logging

### View Logs

```bash
# Backend logs
docker-compose logs -f app

# Frontend logs
docker-compose logs -f frontend

# Nginx logs
docker-compose logs -f nginx

# Database logs
docker-compose logs -f db
```

### Performance Monitoring

```bash
# Check container stats
docker stats

# Check nginx metrics
curl http://localhost/nginx_status
```

---

## Security Checklist

- [ ] SSL certificate installed and renewed
- [ ] `APP_DEBUG=false` in production
- [ ] `APP_ENV=production` set
- [ ] Strong database password configured
- [ ] Redis password configured
- [ ] CORS origins restricted
- [ ] Security headers enabled
- [ ] Rate limiting active
- [ ] Audit logging enabled
- [ ] Daily backups configured
- [ ] Monitoring/alerting configured

---

## Scaling & Performance

### Horizontal Scaling

```bash
# Scale API backend
docker-compose up -d --scale app=3

# Configure Nginx load balancing
```

### Database Replication

```bash
# Configure PostgreSQL replication
# See PostgreSQL documentation for primary/replica setup
```

---

## Troubleshooting

### Port Already in Use
```bash
lsof -i :80
kill -9 <PID>
```

### Database Connection Error
```bash
docker-compose logs db
docker-compose restart db
```

### Frontend Not Loading
```bash
docker-compose logs frontend
docker-compose exec frontend npm run build:ssr
```

---

## Security Updates

### Keep Images Updated
```bash
docker pull php:8.3-fpm-alpine
docker pull node:20-alpine
docker pull postgres:15-alpine
docker pull redis:7-alpine
docker pull nginx:latest

docker-compose rebuilding
docker-compose up -d
```

### Update Dependencies
```bash
# Backend
docker-compose exec app composer update

# Frontend
docker-compose exec frontend npm update
```

---

## Contact & Support

For security issues: security@yourdomain.com  
For technical support: support@yourdomain.com
