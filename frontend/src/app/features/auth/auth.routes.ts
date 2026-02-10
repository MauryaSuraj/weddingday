/**
 * features/auth/auth.routes.ts
 * 
 * Authentication feature routes
 */

import { Routes } from '@angular/router';

export const AUTH_ROUTES: Routes = [
  {
    path: 'login',
    loadComponent: () => import('./login/login.component').then(m => m.LoginComponent),
    data: { title: 'Login' }
  },
  {
    path: 'register',
    loadComponent: () => import('./register/register.component').then(m => m.RegisterComponent),
    data: { title: 'Register' }
  }
];
