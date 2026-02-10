# Frontend Dockerfile
# Multi-stage build for Angular 21 with Angular Universal (SSR)

FROM node:20-alpine AS builder

WORKDIR /app

# Copy package files
COPY package*.json ./

# Install dependencies
RUN npm ci

# Copy source code
COPY . .

# Build Angular application (SSR enabled)
RUN npm run build:ssr

# Production image
FROM node:20-alpine

WORKDIR /app

# Install PM2 for process management
RUN npm install -g pm2

# Copy built application
COPY --from=builder /app/dist ./dist
COPY --from=builder /app/package*.json ./

# Install production dependencies only
RUN npm ci --omit=dev

# Expose port
EXPOSE 4200

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost:4200 || exit 1

# Start application with PM2
CMD ["pm2-runtime", "start", "dist/server/main.js"]
