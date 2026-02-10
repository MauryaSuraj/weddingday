/**
 * app/core/models/auth.model.ts
 * 
 * TypeScript interfaces for authentication.
 * Ensures type safety throughout the application.
 */

export interface LoginRequest {
  email: string;
  password: string;
}

export interface RegisterRequest {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface LoginResponse {
  success: boolean;
  data: {
    user: User;
    token: string;
  };
  timestamp: string;
}

export interface User {
  id: string;
  name: string;
  email: string;
  email_verified_at?: string;
  roles: string[];
  created_at: string;
  updated_at: string;
}

export interface AuthState {
  user: User | null;
  authenticated: boolean;
  loading: boolean;
  error: string | null;
}

export interface ApiResponse<T> {
  success: boolean;
  data: T;
  error?: {
    code: string;
    message: string;
  };
  timestamp: string;
}

export interface PaginationResponse<T> {
  success: boolean;
  data: T[];
  pagination: {
    total: number;
    per_page: number;
    current_page: number;
    last_page: number;
    from: number;
    to: number;
  };
  timestamp: string;
}
