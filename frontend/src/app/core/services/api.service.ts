/**
 * app/core/services/api.service.ts
 * 
 * Generic API service for making HTTP requests.
 * Handles common operations and error handling.
 * 
 * Security:
 * - All requests go through HttpInterceptor
 * - CSRF tokens attached automatically
 * - Cookies sent automatically (HttpOnly)
 * - Timeout configured
 * - Error handling centralized
 */

import { Injectable } from '@angular/core';
import { HttpClient, HttpParams } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { ApiResponse, PaginationResponse } from '../auth/models/auth.model';

@Injectable({
  providedIn: 'root'
})
export class ApiService {
  private apiUrl = environment.apiUrl;

  constructor(private http: HttpClient) {}

  /**
   * GET request
   */
  get<T>(endpoint: string, params?: any): Observable<ApiResponse<T>> {
    let httpParams = new HttpParams();
    if (params) {
      Object.keys(params).forEach(key => {
        httpParams = httpParams.set(key, params[key]);
      });
    }
    return this.http.get<ApiResponse<T>>(`${this.apiUrl}${endpoint}`, { params: httpParams });
  }

  /**
   * GET request with pagination
   */
  getPage<T>(endpoint: string, page: number = 1, perPage: number = 15, params?: any): Observable<PaginationResponse<T>> {
    const allParams = {
      page,
      per_page: perPage,
      ...params
    };
    return this.get<T[]>(endpoint, allParams) as any;
  }

  /**
   * POST request
   */
  post<T>(endpoint: string, data: any): Observable<ApiResponse<T>> {
    return this.http.post<ApiResponse<T>>(`${this.apiUrl}${endpoint}`, data);
  }

  /**
   * PUT request
   */
  put<T>(endpoint: string, data: any): Observable<ApiResponse<T>> {
    return this.http.put<ApiResponse<T>>(`${this.apiUrl}${endpoint}`, data);
  }

  /**
   * PATCH request
   */
  patch<T>(endpoint: string, data: any): Observable<ApiResponse<T>> {
    return this.http.patch<ApiResponse<T>>(`${this.apiUrl}${endpoint}`, data);
  }

  /**
   * DELETE request
   */
  delete<T>(endpoint: string): Observable<ApiResponse<T>> {
    return this.http.delete<ApiResponse<T>>(`${this.apiUrl}${endpoint}`);
  }
}
