// environments/environment.prod.ts - Production environment
// 
// Security-hardened production configuration
// - No debug mode
// - No console logging
// - HTTPS enforcement
// - Secure cookie settings

export const environment = {
  production: true,
  apiUrl: 'https://api.yourdomain.com/api/v1',
  frontendUrl: 'https://yourdomain.com',
  
  // Security & Session
  tokenExpirationHours: 24,
  sessionTimeoutMinutes: 60,
  
  // Debug mode DISABLED in production
  debug: false,
  enableConsoleLogging: false,
};
