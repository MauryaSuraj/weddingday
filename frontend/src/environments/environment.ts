// environments/environment.ts - Development environment

export const environment = {
  production: false,
  apiUrl: 'http://localhost:8000/api/v1',
  frontendUrl: 'http://localhost:4200',
  
  // Security & Session
  tokenExpirationHours: 24,
  sessionTimeoutMinutes: 60,
  
  // Debug mode (disabled in production)
  debug: true,
  enableConsoleLogging: true,
};
