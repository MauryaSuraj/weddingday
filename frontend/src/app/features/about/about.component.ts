/**
 * features/about/about.component.ts
 * 
 * About page component with SEO optimization and structured data.
 * 
 * SEO Features:
 * - Dynamic meta tags
 * - Rich structured data
 * - Semantic HTML
 * - Server-side rendering support
 */

import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { SeoService } from '../../core/services/seo.service';
import { environment } from '../../../environments/environment';

@Component({
  selector: 'app-about',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div class="about-container">
      <!-- Page Header -->
      <header class="page-header">
        <h1>About Our Platform</h1>
        <p class="subtitle">Building secure, scalable solutions for modern enterprises</p>
      </header>

      <!-- Main Content -->
      <main class="about-content">
        <section class="section">
          <h2>Our Mission</h2>
          <p>
            We provide enterprise-grade security combined with modern development practices.
            Our platform is designed from the ground up with security first, ensuring compliance
            with OWASP Top 10, VAPT standards, and industry best practices.
          </p>
        </section>

        <section class="section">
          <h2>Why We're Different</h2>
          <article class="benefit">
            <h3>🔐 Security-First Architecture</h3>
            <p>Every decision is made with security in mind. We follow OWASP guidelines, implement
              proper authentication/authorization, and protect against common vulnerabilities.</p>
          </article>
          <article class="benefit">
            <h3>📈 Performance Optimized</h3>
            <p>Our platform is optimized for performance with LCP < 2.5s, efficient APIs,
              and proper caching strategies.</p>
          </article>
          <article class="benefit">
            <h3>🎯 SEO Ready</h3>
            <p>Server-side rendering, structured data, and semantic HTML ensure excellent
              search engine visibility.</p>
          </article>
          <article class="benefit">
            <h3>🚀 Production Ready</h3>
            <p>Docker-ready deployment, comprehensive logging, and monitoring ensure smooth
              production operations.</p>
          </article>
        </section>

        <section class="section">
          <h2>Technology Stack</h2>
          <div class="tech-stack">
            <div class="tech-item">
              <h3>Backend</h3>
              <p>Laravel 12 (PHP 8.3+)</p>
              <ul>
                <li>Sanctum Authentication</li>
                <li>Role-Based Access Control</li>
                <li>Comprehensive Logging</li>
              </ul>
            </div>
            <div class="tech-item">
              <h3>Frontend</h3>
              <p>Angular 21</p>
              <ul>
                <li>Standalone Components</li>
                <li>Angular Universal (SSR)</li>
                <li>Strict TypeScript Mode</li>
              </ul>
            </div>
            <div class="tech-item">
              <h3>Deployment</h3>
              <p>Docker + Nginx</p>
              <ul>
                <li>HTTPS / TLS</li>
                <li>Container Orchestration</li>
                <li>Zero-downtime Deployment</li>
              </ul>
            </div>
          </div>
        </section>

        <section class="section">
          <h2>Compliance & Standards</h2>
          <div class="compliance">
            <div class="standard">
              <strong>✓ OWASP Top 10</strong> - All vulnerabilities addressed
            </div>
            <div class="standard">
              <strong>✓ VAPT Ready</strong> - Designed for vulnerability assessments
            </div>
            <div class="standard">
              <strong>✓ WCAG 2.1 AA</strong> - Accessibility compliant
            </div>
            <div class="standard">
              <strong>✓ SOC 2 Compatible</strong> - Suitable for enterprise deployment
            </div>
          </div>
        </section>
      </main>
    </div>
  `,
  styles: [`
    .about-container {
      min-height: 100vh;
      background: #f8f9fa;
    }

    .page-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 60px 20px;
      text-align: center;
    }

    .page-header h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    .subtitle {
      font-size: 1.2rem;
      opacity: 0.9;
    }

    .about-content {
      max-width: 900px;
      margin: 0 auto;
      padding: 60px 20px;
    }

    .section {
      margin-bottom: 60px;
    }

    .section h2 {
      font-size: 2rem;
      margin-bottom: 30px;
      color: #333;
    }

    .section p {
      font-size: 1.1rem;
      line-height: 1.8;
      color: #555;
    }

    .benefit {
      background: white;
      padding: 25px;
      margin-bottom: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .benefit h3 {
      color: #667eea;
      margin-bottom: 10px;
    }

    .benefit p {
      margin: 0;
    }

    .tech-stack {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 30px;
      margin-top: 30px;
    }

    .tech-item {
      background: white;
      padding: 25px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .tech-item h3 {
      color: #667eea;
      margin-bottom: 5px;
    }

    .tech-item p {
      font-weight: 600;
      margin-bottom: 15px;
    }

    .tech-item ul {
      list-style: none;
      padding: 0;
    }

    .tech-item li {
      padding: 5px 0;
      padding-left: 20px;
      position: relative;
    }

    .tech-item li:before {
      content: '✓';
      position: absolute;
      left: 0;
      color: #667eea;
      font-weight: bold;
    }

    .compliance {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
      gap: 20px;
    }

    .standard {
      background: white;
      padding: 20px;
      border-radius: 8px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
      .page-header h1 {
        font-size: 1.8rem;
      }

      .section h2 {
        font-size: 1.5rem;
      }
    }
  `]
})
export class AboutComponent implements OnInit {
  constructor(private seoService: SeoService) {}

  ngOnInit(): void {
    // Update SEO meta tags
    this.seoService.updateSEO({
      title: 'About Us',
      description: 'Learn about our secure full-stack platform. Built with security first, OWASP compliant, production-ready architecture for enterprises.',
      keywords: 'about, company, security, technology, enterprise',
      author: 'Your Company',
      image: `${environment.frontendUrl}/assets/og-image.png`,
      url: `${environment.frontendUrl}/about`,
      type: 'website'
    });

    // Set Organization schema
    this.seoService.setStructuredData({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      'name': 'Secure Full-Stack App',
      'url': environment.frontendUrl,
      'description': 'Enterprise-grade secure full-stack platform built with Laravel and Angular',
      'foundingDate': '2024',
      'contactPoint': {
        '@type': 'ContactPoint',
        'contactType': 'Customer Service',
        'email': 'support@yourdomain.com'
      },
      'address': {
        '@type': 'PostalAddress',
        'streetAddress': 'Your Street Address',
        'addressLocality': 'Your City',
        'addressRegion': 'Your State',
        'postalCode': 'Your ZIP',
        'addressCountry': 'US'
      }
    });
  }
}
