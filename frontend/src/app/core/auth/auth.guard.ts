/**
 * app/core/auth/auth.guard.ts
 * 
 * Route guard to protect authenticated routes.
 * Prevents access to protected pages without authentication.
 * 
 * Usage:
 * {
 *   path: 'dashboard',
 *   component: DashboardComponent,
 *   canActivate: [AuthGuard]
 * }
 * 
 * Security:
 * - Server-driven authentication (trusting API)
 * - Automatic redirect to login if not authenticated
 * - Prevents direct URL access to protected routes
 */

import { Injectable } from '@angular/core';
import { Router, CanActivateFn, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { inject } from '@angular/core';
import { AuthService } from './auth.service';

export const AuthGuard: CanActivateFn = (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  // Check if user is authenticated
  if (authService.isAuthenticated) {
    // Check role requirements if specified in route data
    const requiredRoles = route.data['roles'] as string[];
    if (requiredRoles && requiredRoles.length > 0) {
      if (authService.hasAnyRole(...requiredRoles)) {
        return true;
      } else {
        // User doesn't have required role
        router.navigate(['/unauthorized']);
        return false;
      }
    }
    return true;
  }

  // Not authenticated - redirect to login
  router.navigate(['/auth/login'], {
    queryParams: { returnUrl: state.url }
  });
  return false;
};

/**
 * Role guard - checks if user has specific role
 */
export const RoleGuard: CanActivateFn = (route: ActivatedRouteSnapshot, state: RouterStateSnapshot) => {
  const authService = inject(AuthService);
  const router = inject(Router);

  if (!authService.isAuthenticated) {
    router.navigate(['/auth/login']);
    return false;
  }

  const requiredRoles = route.data['roles'] as string[];
  if (requiredRoles && requiredRoles.length > 0) {
    if (authService.hasAnyRole(...requiredRoles)) {
      return true;
    }
  }

  router.navigate(['/unauthorized']);
  return false;
};
