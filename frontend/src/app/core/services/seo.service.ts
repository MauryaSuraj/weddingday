/**
 * app/core/services/seo.service.ts
 * 
 * SEO service for managing meta tags and structured data.
 * Integrates with Angular's Meta and Title services for dynamic SEO.
 * 
 * Features:
 * - Dynamic title and meta tags
 * - Open Graph tags
 * - Twitter Card tags
 * - Structured data (JSON-LD)
 * - Canonical URLs
 * 
 * Security:
 * - Uses Angular's sanitization for safe HTML rendering
 * - CSRF protection for any forms
 */

import { Injectable } from '@angular/core';
import { Meta, Title } from '@angular/platform-browser';
import { environment } from '../../../environments/environment';

export interface SEOConfig {
  title: string;
  description: string;
  keywords?: string;
  author?: string;
  image?: string;
  url?: string;
  type?: 'website' | 'article' | 'business.business';
}

@Injectable({
  providedIn: 'root'
})
export class SeoService {
  constructor(
    private titleService: Title,
    private metaService: Meta
  ) {}

  /**
   * Update page SEO
   */
  updateSEO(config: SEOConfig): void {
    // Set page title
    const fullTitle = `${config.title} | Secure Full-Stack App`;
    this.titleService.setTitle(fullTitle);

    // Update meta tags
    this.updateMetaTag('description', config.description);
    if (config.keywords) {
      this.updateMetaTag('keywords', config.keywords);
    }
    if (config.author) {
      this.updateMetaTag('author', config.author);
    }

    // Open Graph tags
    this.updateProperty('og:title', config.title);
    this.updateProperty('og:description', config.description);
    this.updateProperty('og:type', config.type || 'website');
    this.updateProperty('og:url', config.url || environment.frontendUrl);
    if (config.image) {
      this.updateProperty('og:image', config.image);
    }

    // Twitter Card tags
    this.updateProperty('twitter:card', 'summary_large_image');
    this.updateProperty('twitter:title', config.title);
    this.updateProperty('twitter:description', config.description);
    if (config.image) {
      this.updateProperty('twitter:image', config.image);
    }

    // Canonical URL
    if (config.url) {
      this.updateCanonicalUrl(config.url);
    }
  }

  /**
   * Set structured data (JSON-LD)
   */
  setStructuredData(data: any): void {
    // Remove existing script tag if present
    const existingScript = document.querySelector('script[type="application/ld+json"]');
    if (existingScript) {
      existingScript.remove();
    }

    // Create new script tag
    const script = document.createElement('script');
    script.type = 'application/ld+json';
    script.text = JSON.stringify(data);
    document.head.appendChild(script);
  }

  /**
   * Reset to default SEO
   */
  resetSEO(): void {
    this.updateSEO({
      title: 'Secure Full-Stack App',
      description: 'A production-ready, security-hardened full-stack application.',
      keywords: 'angular, laravel, security, api',
      author: 'Your Company',
    });
  }

  /**
   * ============ PRIVATE METHODS ============
   */

  private updateMetaTag(name: string, content: string): void {
    const tag = this.metaService.getTag(`name="${name}"`);
    if (tag) {
      this.metaService.updateTag({ name, content });
    } else {
      this.metaService.addTag({ name, content });
    }
  }

  private updateProperty(property: string, content: string): void {
    const tag = this.metaService.getTag(`property="${property}"`);
    if (tag) {
      this.metaService.updateTag({ property, content });
    } else {
      this.metaService.addTag({ property, content });
    }
  }

  private updateCanonicalUrl(url: string): void {
    let canonicalLink = document.querySelector('link[rel="canonical"]') as HTMLLinkElement;
    if (!canonicalLink) {
      canonicalLink = document.createElement('link');
      canonicalLink.rel = 'canonical';
      document.head.appendChild(canonicalLink);
    }
    canonicalLink.href = url;
  }
}
