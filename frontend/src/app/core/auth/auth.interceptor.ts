/**
 * app/core/auth/auth.interceptor.ts
 * 
 * HTTP interceptor for security and authentication.
 * 
 * Responsibilities:
 * - Add CSRF token to requests
 * - Handle authentication errors (401/403)
 * - Add security headers
 * - Handle token refresh
 * 
 * Security:
 * - CSRF tokens auto-attached
 * - Cookies sent automatically (HttpOnly managed by browser)
 * - Handles 401/403 responses
 * - Global error handling
 */

import { Injectable } from '@angular/core';
import {
  HttpInterceptor,
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpErrorResponse,
} from '@angular/common/http';
import { Observable, throwError, BehaviorSubject } from 'rxjs';
import { catchError, filter, take, switchMap } from 'rxjs/operators';
import { AuthService } from './auth.service';
import { Router } from '@angular/router';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {
  private isRefreshing = false;
  private refreshTokenSubject = new BehaviorSubject<any>(null);

  constructor(
    private authService: AuthService,
    private router: Router
  ) {}

  intercept(
    req: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    // Add CSRF token if available
    const csrfToken = this.getCsrfToken();
    if (csrfToken && !this.isExcludedUrl(req.url)) {
      req = req.clone({
        setHeaders: {
          'X-CSRF-TOKEN': csrfToken,
        },
      });
    }

    return next.handle(req).pipe(
      catchError((error: HttpErrorResponse) => {
        if (error.status === 401) {
          return this.handle401Error(req, next);
        } else if (error.status === 403) {
          return this.handle403Error(error);
        }
        return throwError(() => error);
      })
    );
  }

  /**
   * Handle 401 Unauthorized responses
   * Attempt token refresh, otherwise logout
   */
  private handle401Error(
    request: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {
    if (!this.isRefreshing) {
      this.isRefreshing = true;
      this.refreshTokenSubject.next(null);

      return this.authService.refreshToken().pipe(
        switchMap((response: any) => {
          this.isRefreshing = false;
          this.refreshTokenSubject.next(response.data.token);
          return next.handle(request);
        }),
        catchError((error) => {
          this.isRefreshing = false;
          this.authService.logout().subscribe(() => {
            this.router.navigate(['/auth/login']);
          });
          return throwError(() => error);
        })
      );
    } else {
      return this.refreshTokenSubject.pipe(
        filter((token) => token != null),
        take(1),
        switchMap((token) => {
          return next.handle(request);
        })
      );
    }
  }

  /**
   * Handle 403 Forbidden responses
   */
  private handle403Error(error: HttpErrorResponse): Observable<never> {
    this.router.navigate(['/unauthorized']);
    return throwError(() => error);
  }

  /**
   * Get CSRF token from meta tag
   */
  private getCsrfToken(): string | null {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : null;
  }

  /**
   * Check if URL should be excluded from interceptor
   */
  private isExcludedUrl(url: string): boolean {
    return url.includes('/auth/login') || url.includes('/auth/register');
  }
}
