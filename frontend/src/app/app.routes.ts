/**
 * app/app.routes.ts
 * 
 * Main application routes with security and SEO considerations.
 * 
 * Structure:
 * - Public routes (home, about, login)
 * - Protected routes (dashboard, profile) with AuthGuard
 * - Admin routes with RoleGuard
 * - Wildcard 404 fallback
 * 
 * Security:
 * - Protected routes use AuthGuard
 * - Admin routes use RoleGuard
 * - Lazy loading for better performance
 */

import { Routes } from '@angular/router';
import { AuthGuard, RoleGuard } from './core/auth/auth.guard';

export const routes: Routes = [
  // ==================== PUBLIC ROUTES ====================
  {
    path: '',
    loadComponent: () => import('./features/home/home.component').then(m => m.HomeComponent),
    data: { title: 'Home' }
  },
  {
    path: 'about',
    loadComponent: () => import('./features/about/about.component').then(m => m.AboutComponent),
    data: { title: 'About Us' }
  },

  // ==================== AUTHENTICATION ROUTES ====================
  {
    path: 'auth',
    loadChildren: () => import('./features/auth/auth.routes').then(m => m.AUTH_ROUTES)
  },

  // ==================== PROTECTED ROUTES ====================
  {
    path: 'dashboard',
    loadComponent: () => import('./features/dashboard/dashboard.component').then(m => m.DashboardComponent),
    canActivate: [AuthGuard],
    data: { title: 'Dashboard' }
  },
  {
    path: 'profile',
    loadComponent: () => import('./features/profile/profile.component').then(m => m.ProfileComponent),
    canActivate: [AuthGuard],
    data: { title: 'Profile' }
  },

  // ==================== ADMIN ROUTES ====================
  {
    path: 'admin',
    canActivate: [RoleGuard],
    data: { roles: ['admin'] },
    children: [
      {
        path: 'users',
        loadComponent: () => import('./features/admin/users/users.component').then(m => m.UsersComponent),
        data: { title: 'Manage Users' }
      }
    ]
  },

  // ==================== ERROR ROUTES ====================
  {
    path: 'unauthorized',
    loadComponent: () => import('./shared/components/unauthorized/unauthorized.component').then(m => m.UnauthorizedComponent),
    data: { title: 'Unauthorized' }
  },
  {
    path: '404',
    loadComponent: () => import('./shared/components/not-found/not-found.component').then(m => m.NotFoundComponent),
    data: { title: 'Not Found' }
  },

  // ==================== WILDCARD ====================
  { path: '**', redirectTo: '/404' }
];
