# Product Requirements Document — Highblossom

**Version:** 1.0  
**Last Updated:** 2026-09-02  
**Stack:** Laravel 13 · PHP 8.2 · Livewire + Flux · Tailwind CSS · Vite

---

## 1. Product Overview

### 1.1 What is Highblossom?

Highblossom is a **customer-facing website and admin portal for a glass/glazing service business**. It enables customers to browse services, request quotes, book appointments, and view galleries, while giving staff a full admin panel to manage content, bookings, inspections, quotes, and site settings.

### 1.2 Target Audience

| Segment | Needs |
|---------|-------|
| **End customers** (vehicle owners, property owners) | Browse glass services, request quotes, book appointments, view portfolio |
| **Admin staff** (managers, technicians, content editors) | Manage services, bookings, inspections, quotes, gallery, blog, SEO, users |
| **Technicians** | View assigned inspections, update status |

### 1.3 Core Value Proposition

> "Professional glass services — quote, book, and manage everything online."

---

## 2. Feature Inventory (Current State)

### 2.1 Public Website

| Feature | Routes | Description |
|---------|--------|-------------|
| **Home** | `GET /` | Hero, featured services, testimonials, partners, CTAs |
| **About Us** | `GET /about-us` | Company story, team, values, hero image |
| **Services** | `GET /services` | List of services with categories, glass types, sub-categories |
| **Gallery** | `GET /gallery`, `GET /gallery/{image}` | Categorized project portfolio with detail view |
| **Quote Request** | `GET /quote`, `POST /quote` | Multi-step form: vehicle details, glass type, service type, images, mobile service option |
| **Contact** | `GET /contact`, `POST /contact` | Contact form with honeypot + rate limiting |
| **Blog** | `GET /blog`, `GET /blog/{slug}` | Posts with categories, tags, SEO, content blocks |
| **Booking** | `GET /bookings/create`, `POST /bookings`, `GET /bookings/{id}/confirmation` | Date/time picker with availability API, signed confirmation link |
| **Legal** | `GET /terms`, `GET /privacy` | Static pages |

### 2.2 Admin Portal (`/admin/*`)

| Module | CRUD | Key Permissions |
|--------|------|-----------------|
| **Dashboard** | — | `access admin panel` |
| **Bookings** | R/U/D | `view bookings`, `update bookings` |
| **Inspections** | C/R/U/D | `view inspections`, `update inspections` |
| **Staff Absences** | R | `manage absences` |
| **Company Settings** | R/U | `view settings`, `update settings` |
| **About Us Content** | R/U | `manage pages` (uses ContentBlocks) |
| **Testimonials** | C/R/U/D | `manage testimonials` |
| **Services** | C/R/U/D | `view services`, `manage services` |
| **Blog Posts** | C/R/U/D | `view blog`, `create blog`, `update blog`, `delete blog` |
| **Categories/Tags** | C/R/U/D | Same as blog |
| **Staff** | C/R/U/D | `manage settings` |
| **Partners** | C/R/U/D | `manage settings` |
| **Gallery** | C/R/U/D | `view gallery`, `manage gallery` |
| **Gallery Categories** | C/R/U/D | `view gallery`, `manage gallery` |
| **Glass Types** | C/R/U/D | `view services`, `manage services` |
| **Glass Sub-Categories** | C/R/U/D + reorder/toggle | `manage services` |
| **Service Types** | C/R/U/D | `view services`, `manage services` |
| **Contact Messages** | R/U/D + mark-read | `view contact messages` |
| **Quotes** | R/U/D + status | `view bookings`, `update bookings` |
| **SEO (Static Routes)** | C/R/U/D | `manage seo` |
| **Users** | C/R/U/D | `manage users` |
| **Roles/Permissions** | C/R/U/D | `manage roles` |
| **Media Library** | R/U/D + upload | — |
| **Profile** | R/U + 2FA | — |

### 2.3 Auth & Security (Laravel Fortify + Passkeys)

- Email/password registration & login
- Email verification
- Password reset
- Two-factor authentication (TOTP + recovery codes)
- Passkeys (WebAuthn)
- Rate limiting on sensitive routes (3/min for quotes, contact, bookings; 30/min for availability API)
- Spatie Permission: roles + granular permissions per admin section

### 2.4 ContentBlocks Package (Local)

A **block-based page builder** (21 block types):

| Block | Purpose |
|-------|---------|
| Accordion, Alert, Carousel, Code, Columns, CTA, Countdown, Divider, Embed, Form, Gallery, Heading, Html, Image, List, Paragraph, Poll, Quote, Table, Tabs, Video | Rich content editing for About Us, Blog, etc. |

### 2.5 Key Integrations

| Service | Purpose |
|---------|---------|
| **Resend** | Transactional email (quotes, bookings, contact) |
| **Nightwatch** | Exception monitoring |
| **QR Code (endroid/qr-code)** | Booking confirmations, etc. |
| **PHP-FFmpeg** | Video processing for ContentBlocks |
| **CKEditor 5** | Rich text editing |
| **Spatie Laravel Permission** | RBAC |
| **Laravel Query Detector** | N+1 detection (dev) |
| **Debugbar, Pail, Telescope alternatives** | Dev tooling |

---

## 3. Data Model (Core Entities)

```
CompanySetting (key-value, cached) ──────────────────┐
                                                     │
User ───< Booking >─── Inspection ── Staff (User)   │
    │         │              │                      │
    │         │              └── staff_id            │
    │         │                                     │
    │         └── user_id (nullable, guest bookings)│
    │                                             │
    └──< Quote (glass_type, sub_category, service_type, image)──┤
                                                                    │
Post ──< Category/Tag ── ContentBlocks (JSON)                     │
    │                                                            │
    └── SEO (SeoStaticRoute)                                      │
                                                                    │
Service ── ServiceType                                              │
    │                                                                │
    └── GlassType ── GlassSubCategory (ordered, togglable)         │
                                                                    │
GalleryImage ── GalleryCategory                                     │
                                                                    │
Testimonial, Partner, Staff (team members)                         │
                                                                    │
ContactMessage                                                     │
Poll / PollVote (ContentBlocks)                                    │
```

---

## 4. User Roles & Permissions

| Role | Typical Permissions |
|------|---------------------|
| **Super Admin** | All permissions |
| **Manager** | Bookings, inspections, quotes, staff, settings, content |
| **Content Editor** | Blog, testimonials, gallery, about-us, SEO |
| **Technician** | View assigned inspections, update status |

Permissions are defined in `config/permission.php` and assigned via `RoleController`.

---

## 5. Business Workflows

### 5.1 Quote → Booking → Inspection Flow

```
Customer                    Staff
   │                          │
   ├─ Quote Request ────────►│ (admin: quotes.index)
   │                          │
   ├─ Booking Request ──────►│ (admin: bookings.index)
   │         │                │
   │         ▼                │
   │    Availability API     │
   │         │                │
   │         ▼                │
   │    Signed Confirmation  │
   │         │                │
   │         ▼                │
   │                    Create Inspection
   │                          │
   │                    Assign Technician
   │                          │
   │                    Complete Inspection
```

### 5.2 Content Publishing

```
Editor → ContentBlocks (About Us, Blog) → Published on public site
```

---

## 6. Non-Functional Requirements

| Category | Requirement |
|----------|-------------|
| **Performance** | Query Detector in dev; eager loading in services; cached company settings |
| **Security** | CSRF, rate limiting, signed URLs for booking confirmation, Fortify auth, XSS sanitization (HtmlSanitizer in ContentBlocks) |
| **Accessibility** | Tailwind + Flux components; semantic HTML; alt text on images |
| **SEO** | Sitemap.xml, robots.txt, per-page SEO (SeoStaticRoute), meta tags, structured data ready |
| **Observability** | Nightwatch (exceptions), Pail (logs), Debugbar (dev) |
| **Deploy** | EnvKit for local; Sail for CI; SQLite default, MySQL/Postgres production |

---

## 7. Current Gaps / Opportunities

| Area | Observation |
|------|-------------|
| **Payments** | No payment integration (Stripe, etc.) for deposits |
| **Notifications** | Email only (Resend); no SMS (Twilio), no in-app notifications |
| **Customer Portal** | No login area for customers to view quote/booking history |
| **Analytics** | No GA4/Plausible integration; no event tracking |
| **Multi-language** | Single locale (en) only |
| **API** | Only internal availability + poll endpoints; no public API |
| **Testing** | Feature tests exist for auth, bookings, quotes, contact; could expand coverage |
| **Content Versioning** | No revision history for ContentBlocks/About Us |

---

## 8. Roadmap Suggestions (Prioritized)

| Priority | Initiative | Effort |
|----------|------------|--------|
| **P0** | Add payment deposits for bookings (Stripe) | M |
| **P0** | Customer portal: view quotes, bookings, invoices | M |
| **P1** | SMS notifications (Twilio) for booking reminders | S |
| **P1** | Analytics (GA4 + custom events) | S |
| **P1** | In-app notifications (admin bell icon) | M |
| **P2** | Public REST API for quotes/bookings | M |
| **P2** | Multi-language (Laravel Localization) | L |
| **P2** | Content versioning/revisions | M |
| **P3** | Automated quote-to-booking conversion | M |
| **P3** | Technician mobile view (PWA) | L |

---

## 9. Development Conventions

- **Architecture:** Route → Controller → Service → Model (thin controllers)
- **Validation:** Form Requests
- **Business Logic:** Services (app/Services) + Actions (app/Actions)
- **Frontend:** Livewire Flux components + Alpine.js; Tailwind v4
- **Code Style:** `vendor/bin/pint --parallel`, `php artisan test`, `npm run build`
- **Docblocks:** Required per `CONVENTIONS.md` (tiered); `declare(strict_types=1)` on all PHP files
- **Testing:** Pest/PHPUnit; run `php artisan test` before commit

---

## 10. Key Files for Onboarding

| File | Purpose |
|------|---------|
| `routes/web.php` | Public routes |
| `routes/admin.php` | Admin routes + permissions |
| `app/Models/` | 23 Eloquent models |
| `app/Services/` | 159 service classes |
| `app/Actions/` | 117 action classes |
| `app/Http/Controllers/Admin/` | Admin controllers |
| `app/Http/Controllers/SiteController.php` | Public site controller (fat — refactor target) |
| `packages/ContentBlocks/` | Block editor package |
| `config/permission.php` | Role/permission definitions |
| `resources/views/site/` | Public Blade templates |
| `resources/views/admin/` | Admin Blade templates |
| `database/seeders/` | Demo data |

---

## Appendix: Environment Variables (Key)

```
APP_NAME=Highblossom
MAIL_MAILER=resend
RESEND_KEY=...
NIGHTWATCH_KEY=...
DB_CONNECTION=mysql
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database
```