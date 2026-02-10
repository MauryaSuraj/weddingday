/**
 * features/home/home.component.ts
 * 
 * Home page component with full SEO optimization.
 * 
 * SEO Features:
 * - Dynamic title and meta tags
 * - Open Graph tags for social sharing
 * - Twitter Card tags
 * - Structured data (Organization schema)
 * - Semantic HTML structure
 * - Server-side rendering (via Angular Universal)
 * 
 * Security:
 * - No sensitive data in meta tags
 * - Safe HTML rendering
 * - HTTPS enforced in production
 */

import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule } from '@angular/router';
import { SeoService } from '../../core/services/seo.service';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [CommonModule, RouterModule],
  template: `
    <div class="home-container">
      <!-- Hero Section -->
      <section class="hero" role="banner">
        <div class="hero-content">
          <h1>Welcome to Secure Full-Stack Platform</h1>
          <p class="lead">Enterprise-grade security meets modern development</p>
          <div class="cta-buttons">
            <a href="/about" class="btn btn-primary">Learn More</a>
            <a *ngIf="!isAuthenticated" href="/auth/login" class="btn btn-secondary">Sign In</a>
            <a *ngIf="isAuthenticated" href="/dashboard" class="btn btn-secondary">Go to Dashboard</a>
          </div>
        </div>
      </section>

      <!-- Features Section -->
      <section class="features">
        <h2>Why Choose Us?</h2>
        <div class="feature-grid">
          <article class="feature-card">
            <h3>🔒 Security First</h3>
            <p>OWASP Top 10 compliant, VAPT ready, production-hardened architecture.</p>
          </article>
          <article class="feature-card">
            <h3>⚡ High Performance</h3>
            <p>Optimized for Core Web Vitals, LCP < 2.5s, instant API responses.</p>
          </article>
          <article class="feature-card">
            <h3>🔍 SEO Optimized</h3>
            <p>Server-side rendering, structured data, perfect Lighthouse score.</p>
          </article>
          <article class="feature-card">
            <h3>🏗️ Modern Stack</h3>
            <p>Laravel 12 backend, Angular 21 frontend, Docker-ready deployment.</p>
          </article>
          <article class="feature-card">
            <h3>📱 Responsive Design</h3>
            <p>Mobile-first approach, touch-optimized, progressive enhancement.</p>
          </article>
          <article class="feature-card">
            <h3>♿ Accessible</h3>
            <p>WCAG 2.1 AA compliant, keyboard navigation, screen reader support.</p>
          </article>
        </div>
      </section>

      <!-- CTA Section -->
      <section class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of organizations using our secure platform.</p>
        <a href="/auth/login" class="btn btn-primary btn-large">Start Free Trial</a>
      </section>
    </div>
  `,
  styles: [`
    .home-container {
      min-height: 100vh;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: #fff;
    }

    .hero {
      padding: 100px 20px;
      text-align: center;
      min-height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .hero h1 {
      font-size: 3rem;
      margin-bottom: 20px;
      font-weight: bold;
    }

    .hero .lead {
      font-size: 1.5rem;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    .cta-buttons {
      display: flex;
      gap: 15px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn {
      padding: 12px 30px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      font-size: 1rem;
    }

    .btn-primary {
      background: #fff;
      color: #667eea;
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .btn-secondary {
      background: transparent;
      color: #fff;
      border: 2px solid #fff;
    }

    .btn-secondary:hover {
      background: #fff;
      color: #667eea;
    }

    .btn-large {
      padding: 15px 40px;
      font-size: 1.1rem;
    }

    .features {
      padding: 80px 20px;
      background: #f8f9fa;
      color: #333;
    }

    .features h2 {
      text-align: center;
      font-size: 2.5rem;
      margin-bottom: 50px;
    }

    .feature-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }

    .feature-card {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .feature-card h3 {
      font-size: 1.5rem;
      margin-bottom: 15px;
      color: #667eea;
    }

    .cta-section {
      background: #333;
      color: #fff;
      padding: 80px 20px;
      text-align: center;
    }

    .cta-section h2 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }

    .cta-section p {
      font-size: 1.1rem;
      margin-bottom: 30px;
      opacity: 0.9;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 2rem;
      }

      .hero .lead {
        font-size: 1.2rem;
      }

      .features h2,
      .cta-section h2 {
        font-size: 1.8rem;
      }
    }
  `]
})
export class HomeComponent implements OnInit {
  isAuthenticated = false;

  constructor(private seoService: SeoService) {}

  ngOnInit(): void {
    // Update SEO meta tags
    this.seoService.updateSEO({
      title: 'Home',
      description: 'Secure Full-Stack Platform - Enterprise-grade security with modern development. OWASP compliant, VAPT ready, production-hardened.',
      keywords: 'security, api, angular, laravel, full-stack, enterprise',
      author: 'Your Company',
      image: `${environment.frontendUrl}/assets/og-image.png`,
      url: `${environment.frontendUrl}/`,
      type: 'website'
    });

    // Set Organization structured data
    this.seoService.setStructuredData({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      'name': 'Secure Full-Stack App',
      'description': 'Enterprise-grade secure full-stack platform',
      'url': environment.frontendUrl,
      'logo': `${environment.frontendUrl}/assets/logo.png`,
      'sameAs': [
        'https://www.facebook.com/yourcompany',
        'https://www.twitter.com/yourcompany',
        'https://www.linkedin.com/company/yourcompany'
      ],
      'contactPoint': {
        '@type': 'ContactPoint',
        'contactType': 'Customer Service',
        'email': 'support@yourdomain.com',
        'telephone': '+1-xxx-xxx-xxxx'
      }
    });
  }
}
