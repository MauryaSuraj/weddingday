/**
 * app/core/auth/auth.service.ts
 * 
 * Authentication service for handling login, logout, and token management.
 * 
 * Security Principles:
 * - No tokens stored in localStorage/sessionStorage
 * - Cookies are HttpOnly (managed by backend)
 * - CSRF tokens auto-attached by interceptor
 * - Server-driven authentication state
 */

import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, Observable, throwError } from 'rxjs';
import { tap, catchError, finalize } from 'rxjs/operators';
import { environment } from '../../../environments/environment';
import { User, LoginRequest, LoginResponse, AuthState, ApiResponse } from './models/auth.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private apiUrl = environment.apiUrl;
  
  // Authentication state management
  private authStateSubject = new BehaviorSubject<AuthState>({
    user: this.loadUserFromStorage(),
    authenticated: !!this.loadUserFromStorage(),
    loading: false,
    error: null,
  });

  public authState$ = this.authStateSubject.asObservable();

  constructor(private http: HttpClient) {
    // Restore auth state from storage on init
    this.restoreAuthState();
  }

  /**
   * Get current authentication state
   */
  get currentAuthState(): AuthState {
    return this.authStateSubject.value;
  }

  /**
   * Check if user is authenticated
   */
  get isAuthenticated(): boolean {
    return this.authStateSubject.value.authenticated;
  }

  /**
   * Get current user
   */
  get currentUser(): User | null {
    return this.authStateSubject.value.user;
  }

  /**
   * Login with email and password
   * 
   * Security:
   * - Password sent only over HTTPS
   * - Response contains token (but not stored in frontend)
   * - Cookie is set by backend (HttpOnly, Secure, SameSite)
   */
  login(credentials: LoginRequest): Observable<LoginResponse> {
    this.authStateSubject.next({
      ...this.authStateSubject.value,
      loading: true,
      error: null,
    });

    return this.http.post<LoginResponse>(`${this.apiUrl}/auth/login`, credentials)
      .pipe(
        tap((response) => {
          // Store user data in memory only (not localStorage)
          this.setAuthState(response.data.user, true);
          this.saveUserToStorage(response.data.user);
        }),
        catchError((error) => {
          const errorMessage = error.error?.error?.message || 'Login failed. Please try again.';
          this.authStateSubject.next({
            ...this.authStateSubject.value,
            error: errorMessage,
          });
          return throwError(() => error);
        }),
        finalize(() => {
          this.authStateSubject.next({
            ...this.authStateSubject.value,
            loading: false,
          });
        })
      );
  }

  /**
   * Register new user
   */
  register(data: any): Observable<ApiResponse<any>> {
    return this.http.post<ApiResponse<any>>(`${this.apiUrl}/auth/register`, data)
      .pipe(
        catchError((error) => {
          const errorMessage = error.error?.error?.message || 'Registration failed.';
          return throwError(() => new Error(errorMessage));
        })
      );
  }

  /**
   * Refresh authentication token
   * 
   * Security:
   * - Called periodically to extend session
   * - Token rotation enabled on backend
   */
  refreshToken(): Observable<ApiResponse<{ token: string; expires_in: number }>> {
    return this.http.post<ApiResponse<any>>(`${this.apiUrl}/auth/refresh`, {})
      .pipe(
        catchError((error) => {
          // If refresh fails, user is logged out
          this.logout();
          return throwError(() => error);
        })
      );
  }

  /**
   * Logout user
   * 
   * Security:
   * - Revokes all backend tokens
   * - Clears frontend state
   * - Cookie is deleted by backend
   */
  logout(): Observable<ApiResponse<any>> {
    return this.http.post<ApiResponse<any>>(`${this.apiUrl}/auth/logout`, {})
      .pipe(
        finalize(() => this.clearAuthState())
      );
  }

  /**
   * Get current authenticated user
   */
  getAuthenticatedUser(): Observable<ApiResponse<User>> {
    return this.http.get<ApiResponse<User>>(`${this.apiUrl}/auth/me`)
      .pipe(
        tap((response) => {
          this.setAuthState(response.data, true);
          this.saveUserToStorage(response.data);
        }),
        catchError((error) => {
          // If request fails with 401, user is not authenticated
          if (error.status === 401) {
            this.clearAuthState();
          }
          return throwError(() => error);
        })
      );
  }

  /**
   * Check if user has a specific role
   */
  hasRole(role: string): boolean {
    return this.currentUser?.roles.includes(role) ?? false;
  }

  /**
   * Check if user has any of the given roles
   */
  hasAnyRole(...roles: string[]): boolean {
    return roles.some(role => this.hasRole(role));
  }

  /**
   * Check if user is admin
   */
  isAdmin(): boolean {
    return this.hasRole('admin');
  }

  /**
   * ============ PRIVATE METHODS ============
   */

  /**
   * Set authentication state
   */
  private setAuthState(user: User, authenticated: boolean): void {
    this.authStateSubject.next({
      user,
      authenticated,
      loading: false,
      error: null,
    });
  }

  /**
   * Clear authentication state
   */
  private clearAuthState(): void {
    this.authStateSubject.next({
      user: null,
      authenticated: false,
      loading: false,
      error: null,
    });
    this.removeUserFromStorage();
  }

  /**
   * Save user to session storage (for page reload recovery)
   * 
   * Security:
   * - SessionStorage used (cleared on browser close)
   * - No sensitive data stored
   * - Only user profile information
   */
  private saveUserToStorage(user: User): void {
    try {
      sessionStorage.setItem('auth_user', JSON.stringify(user));
    } catch (error) {
      console.error('Failed to save user to storage:', error);
    }
  }

  /**
   * Load user from session storage
   */
  private loadUserFromStorage(): User | null {
    try {
      const stored = sessionStorage.getItem('auth_user');
      return stored ? JSON.parse(stored) : null;
    } catch (error) {
      console.error('Failed to load user from storage:', error);
      return null;
    }
  }

  /**
   * Remove user from session storage
   */
  private removeUserFromStorage(): void {
    sessionStorage.removeItem('auth_user');
  }

  /**
   * Restore authentication state on app init
   */
  private restoreAuthState(): void {
    const user = this.loadUserFromStorage();
    if (user) {
      // Verify user is still authenticated with backend
      this.getAuthenticatedUser().subscribe();
    }
  }
}
