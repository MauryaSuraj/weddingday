import { ApplicationConfig, importProvidersFrom } from '@angular/core';
import { provideRouter, withPreloading, PreloadAllModules, withDebugTracing } from '@angular/router';
import { provideAnimations } from '@angular/platform-browser/animations';
import { provideHttpClient, withInterceptors, HTTP_INTERCEPTORS } from '@angular/common/http';
import { routes } from './app.routes';

// Import custom interceptors
import { AuthService } from './core/auth/auth.service';
import { ApiService } from './core/services/api.service';
import { SeoService } from './core/services/seo.service';

export const appConfig: ApplicationConfig = {
  providers: [
    provideRouter(
      routes,
      withPreloading(PreloadAllModules),
      // withDebugTracing() // Uncomment for routing debug info
    ),
    provideAnimations(),
    provideHttpClient(
      // Add HTTP interceptors here if needed
      // withInterceptors([httpInterceptor])
    ),
    AuthService,
    ApiService,
    SeoService
  ]
};
