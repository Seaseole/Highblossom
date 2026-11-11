# Graph Report - Highblossom  (2026-11-10)

## Corpus Check
- Large corpus: 1298 files · ~456,108 words. Semantic extraction will be expensive (many Claude tokens). Consider running on a subfolder.

## Summary
- 4977 nodes · 8980 edges · 1046 communities (138 shown, 908 thin omitted)
- Extraction: 97% EXTRACTED · 3% INFERRED · 0% AMBIGUOUS · INFERRED: 311 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Admin JS Behaviors
- Booking Widget JS
- Site Alpine Components
- Core Schema Migrations
- Sign-in Auth Services
- Inspection Admin CRUD
- System Overview Package Catalog
- Roles & Permission Migrations
- Quote Admin
- Session Telemetry Enums
- Booking Create Actions
- Auth Form Requests
- Booking Closures & Service Types
- Profile Update Requests
- Admin Menu Builder
- Public Site Controller
- Booking Notification Mailables
- Content Block: Alert
- System Overview Probe Items
- SEO Sitemap & Posts
- Settings & Upload Services
- Contact & Quote Actions
- System Overview Probe Registry
- Gallery Admin
- Nav Layout & Search Admin
- Testimonial Admin
- Availability Calculation
- Partner Admin
- Gallery Category Admin
- Polls & Sort Order
- User Session Model
- Content Block: Carousel
- Database Seeders
- Booking Availability Contracts
- Eloquent Models
- Audit Action Migrations
- ContentBlocks Package Tests
- Contact Message Admin
- Glass Type Admin
- Media URL Accessors
- System Overview File Scanner
- Testimonials Migration
- Media Library Admin
- System Overview Controller
- Dashboard Service
- Fortify User Creation
- SEO Static Route DTOs
- Docker Compose Services
- HTMLPurifier & Accordion Block
- Password Policy Admin
- Service Admin
- Password Policy Requests
- Tag Admin
- Consent Middleware
- Application Version Model
- Passkey Login Failures
- Validation Rules & Requests
- Orphaned Upload Cleanup
- About Us Admin
- Composer Dependencies
- Application Version Admin
- Category Admin
- Gallery Category Controller
- Package Catalog Counts
- Design System Documentation
- Content Block: CTA
- Quote Email Notifications
- Blog Livewire Filters
- Service Providers
- NPM Dependencies
- app/Http
- app/Http
- app/Services
- app/Services
- packages/ContentBlocks
- app/Http
- (root)
- app/Services
- packages/ContentBlocks
- resources/js
- app/Events
- app/Support
- app/Support
- app/Http
- app/Http
- packages/ContentBlocks
- app/Livewire
- database/factories
- .github/workflows
- AGENTS.md
- app/Actions
- app/Actions
- app/Console
- app/Services
- app/Services
- app/Http
- app/Http
- app/Livewire
- app/Support
- app/Http
- app/Models
- app/Services
- app/Services
- app/Support
- composer.json
- app/Services
- app/Enums
- app/Models
- app/Services
- app/Models
- app/Services
- resources/js
- app/Livewire
- app/Models
- app/Services
- app/Support
- composer.json
- packages/ContentBlocks
- app/Console
- app/Enums
- app/Http
- app/Http
- app/Support
- DESIGN.md
- DESIGN.md
- resources/js
- app/Actions
- app/Http
- app/Services
- composer.json
- packages/ContentBlocks
- resources/js
- database/factories
- app/Enums
- app/Http
- app/Services
- app/Support
- app/Support
- resources/js
- app/Enums
- app/Livewire
- app/Notifications
- app/Services
- app/Support
- database/migrations
- packages/ContentBlocks
- app/Enums
- app/Models
- app/Services
- resources/js
- package.json
- resources/js
- AGENTS.md
- database/migrations
- packages/ContentBlocks
- packages/ContentBlocks
- resources/views
- public/js
- public/js
- resources/js
- resources/js
- resources/js
- app/Services
- composer.json
- package.json
- package.json
- resources/js
- app/Actions
- app/Livewire
- composer.json
- database/migrations
- docker/entrypoint.sh
- packages/ContentBlocks
- app/Enums
- app/Http
- app/Models
- resources/views
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- resources/views
- resources/js
- resources/js
- resources/views
- resources/views
- app/Http
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- database/migrations
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- package.json
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/js
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views
- resources/views

## God Nodes (most connected - your core abstractions)
1. `User` - 106 edges
2. `Booking` - 84 edges
3. `Post` - 62 edges
4. `AbstractBlock` - 54 edges
5. `Controller` - 52 edges
6. `SettingsManager` - 49 edges
7. `ProbeItem` - 48 edges
8. `GalleryImage` - 44 edges
9. `AvailabilityService` - 41 edges
10. `Inspection` - 36 edges

## Surprising Connections (you probably didn't know these)
- `Local packages/ContentBlocks package dependency` --references--> `Content Blocks package (highblossom/content-blocks)`  [EXTRACTED]
  AGENTS.md → packages/ContentBlocks/README.md
- `ContentBlockCleanupService (extractFilePathsFromBlock, cleanupOrphanedUploads, cleanupAllTempFiles)` --shares_data_with--> `Content Blocks package (highblossom/content-blocks)`  [INFERRED]
  CLAUDE.md → packages/ContentBlocks/README.md
- `AboutUsService` --references--> `HtmlSanitizer`  [EXTRACTED]
  app/Services/AboutUsService.php → packages/ContentBlocks/src/Services/HtmlSanitizer.php
- `Highblossom — Copilot Instructions` --references--> `Content Blocks package (highblossom/content-blocks)`  [EXTRACTED]
  .github/copilot-instructions.md → packages/ContentBlocks/README.md
- `v1 rollout plan: swap the layout, then the components, never globally` --references--> `Complexity Hotspots Table (cyclomatic / lines)`  [EXTRACTED]
  DESIGN.md → .github/copilot-instructions.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Laravel Brain generated AI-context file set** — github_copilot_instructions_copilot_instructions, agents_agents_md_agent_contract, claude_claude_md_claude_md, conventions_conventions_md_conventions_md, claude_claude_md_laravel_brain_commands [EXTRACTED 1.00]
- **Design language v1: additive token layer, scoped opt-in, frozen legacy namespaces** — design_ui_token_layer_v1, design_frozen_legacy_token_layers, design_hb_ui_body_scope, design_unlayered_css_specificity, design_ui_component_library, design_dom_contracts [EXTRACTED 1.00]
- **Admin surface redesign programme (day desk and scoring bench on one scoped layer)** — design_day_desk, design_scoring_bench, design_dash_token_layer, design_day_rail, design_score_line_mark, design_admin_sheet_js, design_button_emphasis_ladder [EXTRACTED 1.00]

## Communities (1046 total, 908 thin omitted)

### Community 0 - "Admin JS Behaviors"
Cohesion: 0.03
Nodes (12): cycleTheme(), finishDrag(), hasOverflow(), isCollapsed(), moveBlock(), moveBlockTo(), openDialogs, overflows() (+4 more)

### Community 1 - "Booking Widget JS"
Cohesion: 0.06
Nodes (48): bookThisWeek(), canLeave(), canSubmit(), clampStep(), clearDraft(), contextQuery(), damageLabel(), dayState() (+40 more)

### Community 2 - "Site Alpine Components"
Cohesion: 0.07
Nodes (44): advance(), apply(), cancel(), choose(), clean(), close(), countUp(), enterCopy() (+36 more)

### Community 3 - "Core Schema Migrations"
Cohesion: 0.06
Nodes (29): {closure#1}(), {closure#2}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}() (+21 more)

### Community 4 - "Sign-in Auth Services"
Cohesion: 0.06
Nodes (4): AuthenticateSignIn, ProfileController, User, ProfileService

### Community 5 - "Inspection Admin CRUD"
Cohesion: 0.08
Nodes (10): UpdateInspectionAction, InspectionController, InspectionController, StoreInspectionNoteRequest, StoreInspectionRequest, UpdateInspectionRequest, InspectionNoteResource, InspectionResource (+2 more)

### Community 6 - "System Overview Package Catalog"
Cohesion: 0.05
Nodes (10): ProbeCost, Heavy, Light, {closure#2}(), ProbeStatus, Fail, Info, Ok (+2 more)

### Community 7 - "Roles & Permission Migrations"
Cohesion: 0.07
Nodes (8): AssignRoleToUser, RoleController, RoleRequest, RoleService, {closure#1}(), {closure#2}(), {closure#3}(), RolesAndPermissionsSeeder

### Community 8 - "Quote Admin"
Cohesion: 0.06
Nodes (11): QuoteController, {closure#12}(), {closure#13}(), {closure#2}(), {closure#4}(), {closure#8}(), QuoteController, QuoteStatusRequest (+3 more)

### Community 9 - "Session Telemetry Enums"
Cohesion: 0.08
Nodes (14): SessionEndReason, EXPIRED, IDLE_TIMEOUT, LOGOUT, ORPHANED, PASSWORD_CHANGED, REVOKED_BY_ADMIN, REVOKED_BY_USER (+6 more)

### Community 10 - "Booking Create Actions"
Cohesion: 0.07
Nodes (8): {closure#1}(), StoreInspectionAction, BookingController, BookingController, UpdateBookingRequest, BookingResource, BookingEvent, BookingTimeline

### Community 11 - "Auth Form Requests"
Cohesion: 0.07
Nodes (9): LoginRequest, {closure#1}(), SendPasswordResetLinkRequest, {closure#1}(), {closure#10}(), {closure#7}(), {closure#9}(), CanonicalLoginRateLimiter (+1 more)

### Community 12 - "Booking Closures & Service Types"
Cohesion: 0.09
Nodes (8): BookingClosureController, ServiceTypeController, StaffAbsenceController, StaffController, ServiceTypeRequest, SiteFooterComposer, ServiceType, ServiceTypeService

### Community 13 - "Profile Update Requests"
Cohesion: 0.07
Nodes (7): AccountDeleteRequest, AppearanceRequest, BulkPostStatusRequest, ImageUploadRequest, ProfileUpdateRequest, RevokeOwnSessionRequest, LoginRequest

### Community 14 - "Admin Menu Builder"
Cohesion: 0.07
Nodes (5): AdminMenu, AdminSidebar, AdminTopbar, {closure#3}(), {closure#6}()

### Community 15 - "Public Site Controller"
Cohesion: 0.10
Nodes (3): SiteController, SiteService, HeroSlides

### Community 16 - "Booking Notification Mailables"
Cohesion: 0.14
Nodes (8): BookingCancelledClientMail, BookingCompletedClientMail, BookingConfirmationMail, BookingConfirmedClientMail, BookingRescheduledClientMail, QuoteSubmittedMail, TestEmail, TestMail

### Community 17 - "Content Block: Alert"
Cohesion: 0.07
Nodes (6): AlertBlock, CodeBlock, CountdownBlock, DividerBlock, FormBlock, AbstractBlock

### Community 18 - "System Overview Probe Items"
Cohesion: 0.11
Nodes (6): ProbeItem, {closure#1}(), {closure#3}(), {closure#4}(), PostureProbe, SessionProbe

### Community 19 - "SEO Sitemap & Posts"
Cohesion: 0.09
Nodes (8): {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#2}(), {closure#3}(), PostController, Post

### Community 21 - "Contact & Quote Actions"
Cohesion: 0.09
Nodes (6): SendContactMessage, {closure#1}(), StoreQuoteAction, ContactFormRequest, QuoteFormRequest, IdempotencyService

### Community 22 - "System Overview Probe Registry"
Cohesion: 0.14
Nodes (8): {closure#2}(), ProbeResult, {closure#1}(), ProbeRunner, {closure#1}(), {closure#2}(), {closure#3}(), SystemProbe

### Community 23 - "Gallery Admin"
Cohesion: 0.09
Nodes (6): GalleryController, GalleryController, GalleryItemRequest, GalleryImageResource, GalleryService, MediaRegistryService

### Community 24 - "Nav Layout & Search Admin"
Cohesion: 0.09
Nodes (9): NavLayoutController, SearchController, SortOrderController, ThemeController, DashboardController, Controller, PartnerController, StaffController (+1 more)

### Community 25 - "Testimonial Admin"
Cohesion: 0.10
Nodes (6): TestimonialController, TestimonialController, TestimonialRequest, TestimonialResource, Testimonial, TestimonialService

### Community 27 - "Partner Admin"
Cohesion: 0.10
Nodes (5): PartnerController, PartnerController, PartnerResource, Partner, SortOrderRules

### Community 28 - "Gallery Category Admin"
Cohesion: 0.09
Nodes (7): GalleryCategoryController, StaffController, TaxonomyController, CategoryResource, GalleryCategoryResource, StaffResource, Staff

### Community 29 - "Polls & Sort Order"
Cohesion: 0.06
Nodes (10): SortOrderRenumberPlan, {closure#1}(), {closure#3}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#3}(), {closure#4}() (+2 more)

### Community 30 - "User Session Model"
Cohesion: 0.10
Nodes (9): UserSession, {closure#1}(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), {closure#5}(), {closure#6}() (+1 more)

### Community 31 - "Content Block: Carousel"
Cohesion: 0.08
Nodes (5): CarouselBlock, ColumnsBlock, TabsBlock, BlockRenderer, {closure#1}()

### Community 32 - "Database Seeders"
Cohesion: 0.08
Nodes (10): AboutUsContentSeeder, ApplicationVersionSeeder, CompanyDataSeeder, DatabaseSeeder, GalleryCategorySeeder, GalleryJourneySeeder, GlassTypeSeeder, SeoStaticRouteSeeder (+2 more)

### Community 33 - "Booking Availability Contracts"
Cohesion: 0.12
Nodes (3): BookingAvailabilityController, BookingCalendarService, AvailabilityServiceInterface

### Community 34 - "Eloquent Models"
Cohesion: 0.13
Nodes (5): {closure#1}(), BookingClosedDay, MediaRegistry, PasswordPolicyAudit, StaffAbsence

### Community 35 - "Audit Action Migrations"
Cohesion: 0.07
Nodes (8): {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}()

### Community 36 - "ContentBlocks Package Tests"
Cohesion: 0.10
Nodes (5): ParagraphBlock, {closure#1}(), ContentBlocks, BlockRegistry, BlockRegistryTest

### Community 37 - "Contact Message Admin"
Cohesion: 0.12
Nodes (6): ContactMessageController, {closure#6}(), ContactMessageController, ContactMessageResource, ContactMessage, ContactMessageService

### Community 38 - "Glass Type Admin"
Cohesion: 0.11
Nodes (4): GlassTypeController, GlassTypeRequest, GlassType, GlassTypeService

### Community 39 - "Media URL Accessors"
Cohesion: 0.10
Nodes (3): GalleryBlock, ImageBlock, MediaUrl

### Community 40 - "System Overview File Scanner"
Cohesion: 0.11
Nodes (4): BoundedSizeScanner, {closure#1}(), {closure#1}(), FootprintProbe

### Community 41 - "Testimonials Migration"
Cohesion: 0.07
Nodes (7): {closure#1}(), {closure#1}(), {closure#2}(), {closure#1}(), {closure#1}(), {closure#1}(), {closure#1}()

### Community 42 - "Media Library Admin"
Cohesion: 0.10
Nodes (4): {closure#1}(), MediaLibraryController, GalleryImage, MediaLibraryService

### Community 43 - "System Overview Controller"
Cohesion: 0.12
Nodes (4): SystemOverviewController, WarmSystemOverviewProbe, ProbeRegistry, SystemOverviewExport

### Community 45 - "Fortify User Creation"
Cohesion: 0.10
Nodes (5): CreateNewUser, ResetUserPassword, PasswordValidationRules, ProfileValidationRules, PasswordUpdateRequest

### Community 46 - "SEO Static Route DTOs"
Cohesion: 0.15
Nodes (4): SeoStaticRoute, SeoMetadata, {closure#1}(), SeoInjectionService

### Community 47 - "Docker Compose Services"
Cohesion: 0.07
Nodes (26): build, depends_on, environment, ports, environment, image, volumes, APP_DEBUG (+18 more)

### Community 48 - "HTMLPurifier & Accordion Block"
Cohesion: 0.11
Nodes (3): AccordionBlock, HtmlBlock, HtmlSanitizer

### Community 49 - "Password Policy Admin"
Cohesion: 0.13
Nodes (5): PasswordPolicyController, PasswordPolicyRequest, {closure#1}(), {closure#1}(), PasswordPolicy

### Community 50 - "Service Admin"
Cohesion: 0.14
Nodes (5): {closure#10}(), ServiceController, ServiceRequest, Service, ServiceService

### Community 51 - "Password Policy Requests"
Cohesion: 0.12
Nodes (6): {closure#1}(), {closure#1}(), TwoFactorChallengeRequest, {closure#1}(), {closure#1}(), StoreBlockContentRequest

### Community 52 - "Tag Admin"
Cohesion: 0.13
Nodes (4): TagController, TagRequest, Tag, TagService

### Community 53 - "Consent Middleware"
Cohesion: 0.13
Nodes (7): EnsureConsentRecorded, EnsureEmailIsVerifiedWhenRequired, EnsureRegistrationIsEnabled, ShareThemePreference, ThrottleUsernameAvailability, FailedPasswordConfirmationResponse, NeutralPasswordResetLinkRequestResponse

### Community 54 - "Application Version Model"
Cohesion: 0.10
Nodes (5): ApplicationVersion, MediaUsage, PollVote, {closure#1}(), {closure#2}()

### Community 55 - "Passkey Login Failures"
Cohesion: 0.13
Nodes (5): ResolvePasskeyLoginFailure, BookingEventResource, TagResource, media_url(), {closure#4}()

### Community 56 - "Validation Rules & Requests"
Cohesion: 0.11
Nodes (4): MediaLibraryRequest, SafeStoredUploadPath, SortOrderIsUnique, CompanyValidationRules

### Community 57 - "Orphaned Upload Cleanup"
Cohesion: 0.16
Nodes (4): CleanupOrphanedUploads, CleanupTempUploadsCommand, {closure#1}(), ContentBlockCleanupService

### Community 58 - "About Us Admin"
Cohesion: 0.13
Nodes (5): AboutUsController, AboutUsRequest, AboutUsContent, {closure#1}(), AboutUsService

### Community 59 - "Composer Dependencies"
Cohesion: 0.08
Nodes (24): require, arpanihan/auditify, devakshay/scheduler-list-laravel, endroid/qr-code, highblossom/content-blocks, intervention/image, jenssegers/agent, jeroennoten/laravel-adminlte (+16 more)

### Community 60 - "Application Version Admin"
Cohesion: 0.15
Nodes (3): ApplicationVersionController, StoreApplicationVersionRequest, ApplicationVersionService

### Community 61 - "Category Admin"
Cohesion: 0.15
Nodes (4): CategoryController, CategoryRequest, Category, CategoryService

### Community 62 - "Gallery Category Controller"
Cohesion: 0.15
Nodes (4): GalleryCategoryController, GalleryCategoryRequest, GalleryCategory, GalleryCategoryService

### Community 64 - "Design System Documentation"
Cohesion: 0.12
Nodes (13): AvailabilityService (slot span, bay capacity, closure dates), The day desk — admin dashboard and System Overview, The Day Rail signature: every job is a span whose length is its duration, Glass Cards component treatment, Highblossom Design System, Creative North Star: Precision Fitting as a Digital Interface, Internal users: admin staff managing quotes, gallery and service requests, Offer: windscreen repair/replacement, mobile fitting, ADAS calibration, heavy-machinery glazing (+5 more)

### Community 65 - "Content Block: CTA"
Cohesion: 0.09
Nodes (4): CTABlock, PollBlock, QuoteBlock, {closure#2}()

### Community 66 - "Quote Email Notifications"
Cohesion: 0.16
Nodes (3): SendQuoteEmailNotificationsAction, GlobalSettingsComposer, SettingsManager

### Community 67 - "Blog Livewire Filters"
Cohesion: 0.10
Nodes (3): ActiveFilters, SearchForm, PasskeyRecoveryBanner

### Community 68 - "Service Providers"
Cohesion: 0.13
Nodes (5): AppServiceProvider, FortifyServiceProvider, PasswordRulesServiceProvider, {closure#1}(), SeoServiceProvider

### Community 69 - "NPM Dependencies"
Cohesion: 0.13
Nodes (20): dependencies, alpinejs@^3.15.11, autoprefixer@^10.4.20, bootstrap@^4.6.0, @ckeditor/ckeditor5-build-classic@^41.4.2, concurrently@^9.0.1, jquery@^3.7.0, popper.js@^1.16.1 (+12 more)

### Community 70 - "app/Http"
Cohesion: 0.14
Nodes (3): UserSessionController, RevokeSessionRequest, SessionFilterRequest

### Community 71 - "app/Http"
Cohesion: 0.11
Nodes (3): SeoController, SeoSettingsRequest, SeoStaticRouteRequest

### Community 72 - "app/Services"
Cohesion: 0.14
Nodes (6): BlogSearchController, BlogSearchService, {closure#2}(), {closure#3}(), {closure#5}(), {closure#6}()

### Community 73 - "app/Services"
Cohesion: 0.18
Nodes (3): Booking, BookingIcsService, {closure#1}()

### Community 75 - "app/Http"
Cohesion: 0.22
Nodes (3): AuthController, UserResource, StaffTwoFactorChallengeService

### Community 76 - "(root)"
Cohesion: 0.11
Nodes (3): LogoutController, {closure#2}(), {closure#3}()

### Community 78 - "packages/ContentBlocks"
Cohesion: 0.10
Nodes (19): autoload, autoload-dev, psr-4, psr-4, description, extra, laravel, providers (+11 more)

### Community 79 - "resources/js"
Cohesion: 0.12
Nodes (20): addChip(), addSelected(), appendRow(), close(), hoverIn(), hoverOut(), isMouse(), isSelected() (+12 more)

### Community 80 - "app/Events"
Cohesion: 0.13
Nodes (4): BookingCreatedEvent, ContactMessageReceived, SendContactMessageEmail, BlockFormSubmitted

### Community 81 - "app/Support"
Cohesion: 0.15
Nodes (5): BackfillUsernames, {closure#2}(), {closure#1}(), {closure#1}(), Username

### Community 82 - "app/Support"
Cohesion: 0.16
Nodes (7): DeviceType, BOT, DESKTOP, MOBILE, TABLET, UNKNOWN, DeviceInformation

### Community 83 - "app/Http"
Cohesion: 0.14
Nodes (3): UserController, UserRequest, UserService

### Community 84 - "app/Http"
Cohesion: 0.12
Nodes (4): ConsentController, PollController, ConsentRequest, Poll

### Community 87 - "database/factories"
Cohesion: 0.14
Nodes (6): BookingClosedDayFactory, BookingFactory, InspectionFactory, PartnerFactory, StaffAbsenceFactory, StaffFactory

### Community 88 - ".github/workflows"
Cohesion: 0.11
Nodes (18): jobs, quality, name, on, pull_request, push, permissions, contents (+10 more)

### Community 89 - "AGENTS.md"
Cohesion: 0.18
Nodes (7): AGENTS.md — AI Agent Contract, Local packages/ContentBlocks package dependency, SiteController (documented Fat Class), Laravel Brain tooling: brain:scan, brain:generate-rules, brain:export-context, StoreQuoteAction@execute (Fat Method, handleImageUpload), Documented Code Smells (N+1, Fat Method, Fat Class), Highblossom — Copilot Instructions

### Community 90 - "app/Actions"
Cohesion: 0.19
Nodes (4): BuildSitemap, {closure#1}(), {closure#2}(), SeoController

### Community 91 - "app/Actions"
Cohesion: 0.18
Nodes (3): {closure#1}(), StoreBookingAction, BookingController

### Community 92 - "app/Console"
Cohesion: 0.19
Nodes (3): {closure#1}(), HeroVariantsCommand, PreviewSortOrderRenumber

### Community 94 - "app/Services"
Cohesion: 0.12
Nodes (3): HeroStyle, Cinematic, {closure#1}()

### Community 95 - "app/Http"
Cohesion: 0.15
Nodes (4): SmtpSettingController, SmtpSettingRequest, TestEmailRequest, SmtpSettingService

### Community 96 - "app/Http"
Cohesion: 0.13
Nodes (3): PostController, PostStatusRequest, PostResource

### Community 99 - "app/Http"
Cohesion: 0.17
Nodes (3): {closure#1}(), CompanySettingController, CompanySettingRequest

### Community 104 - "composer.json"
Cohesion: 0.12
Nodes (15): autoload-dev, psr-4, description, extra, laravel, keywords, dont-discover, license (+7 more)

### Community 106 - "app/Enums"
Cohesion: 0.22
Nodes (4): AdminNavLayout, Sidebar, Top, AdminNav

### Community 111 - "resources/js"
Cohesion: 0.14
Nodes (15): allSelected(), appendPickedImages(), findBlockById(), getAttributePath(), guardSubmit(), init(), layout(), measure() (+7 more)

### Community 113 - "app/Models"
Cohesion: 0.24
Nodes (5): CompanySetting, {closure#1}(), down(), setSaturdayClosed(), up()

### Community 116 - "composer.json"
Cohesion: 0.14
Nodes (14): require-dev, albertoarena/laravel-truss, beyondcode/laravel-query-detector, fakerphp/faker, fruitcake/laravel-debugbar, laramint/laravel-brain, laravel/boost, laravel/doctor (+6 more)

### Community 118 - "app/Console"
Cohesion: 0.31
Nodes (3): {closure#1}(), {closure#2}(), MediaMigrateToS3

### Community 119 - "app/Enums"
Cohesion: 0.19
Nodes (7): LoginMethod, PASSKEY, PASSWORD, REMEMBER, TWO_FACTOR, UNKNOWN, RecordSessionLogin

### Community 121 - "app/Http"
Cohesion: 0.22
Nodes (3): VideoUploadController, VideoUploadRequest, VideoUploadService

### Community 125 - "DESIGN.md"
Cohesion: 0.22
Nodes (8): x-admin.confirm-dialog / .hb-modal overlay token fix, resources/js/admin-sheet.js marks engine, --dash-* token layer scoped to .hb-dash / .hb-desk / .hb-sheet, DOM contracts owned by js/tests, not styling, The measured row: 20px gutter | 13rem meta | 1fr control, ProfileController::destroy delete-account 405 defect, The score line: a red rule on every row that diverges from stored, The scoring bench — Company Settings and Profile

### Community 126 - "DESIGN.md"
Cohesion: 0.21
Nodes (7): CompanySettingRequest has no email rule, Frozen legacy token namespaces (Flux/Material-3, admin, site, booking), .hb-ui body scope via x-layouts::base, Photographic hero surface: the one scoped --hero-* exception, v1 rollout plan: swap the layout, then the components, never globally, x-ui.* component library (button, input, field, card, link, alert-banner, ...), Design language v1 — the `ui` token layer

### Community 127 - "resources/js"
Cohesion: 0.17
Nodes (13): at(), choose(), groups(), hide(), isEmpty(), move(), pages(), remember() (+5 more)

### Community 134 - "composer.json"
Cohesion: 0.17
Nodes (12): scripts, ci:check, dev, lint, lint:check, post-autoload-dump, post-create-project-cmd, post-root-package-install (+4 more)

### Community 135 - "packages/ContentBlocks"
Cohesion: 0.23
Nodes (10): Highblossom\ContentBlocks\Services\AbstractBlock (getType, getValidationRules, getDefaultAttributes), Auto-registration through a single Service Provider, Auto-generated @cb* Blade directives, Default block types (paragraph, image, heading, quote, code, list, cta, video), Highblossom\ContentBlocks\Services\BlockRegistry, Highblossom\ContentBlocks\Services\BlockRenderer (render / renderMany), Published configuration: directive_prefix, view_namespace, auto_register, cache_registry, Highblossom\ContentBlocks\Facades\ContentBlocks (+2 more)

### Community 136 - "resources/js"
Cohesion: 0.32
Nodes (11): CANCELLED_ERRORS, clearRecovery(), csrfToken(), errorBox(), invitePasswordSignIn(), request(), showError(), showRecovery() (+3 more)

### Community 138 - "app/Enums"
Cohesion: 0.18
Nodes (8): VideoSourceType, DAILYMOTION, DIRECT_URL, FACEBOOK, LOCAL_FILE, UNKNOWN, VIMEO, YOUTUBE

### Community 141 - "app/Services"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#5}(), {closure#7}(), {closure#8}()

### Community 144 - "resources/js"
Cohesion: 0.22
Nodes (11): editableTypes(), errorsFor(), invalidBlockLabels(), invalidHint(), isEditableType(), isEmptyValue(), isInvalid(), label() (+3 more)

### Community 145 - "app/Enums"
Cohesion: 0.22
Nodes (6): BookingServiceType, Fleet, HeavyMachinery, SideRearGlass, Windscreen, {closure#6}()

### Community 148 - "app/Services"
Cohesion: 0.20
Nodes (4): {closure#1}(), {closure#2}(), {closure#3}(), {closure#9}()

### Community 150 - "database/migrations"
Cohesion: 0.33
Nodes (8): {closure#1}(), {closure#2}(), normaliseAttributes(), normaliseBlocks(), normalisePostContentBlocks(), normaliseSimpleColumns(), tableHasColumn(), up()

### Community 152 - "app/Enums"
Cohesion: 0.22
Nodes (6): BookingDamageType, Chip, Crack, Other, SealLeak, Shattered

### Community 156 - "resources/js"
Cohesion: 0.22
Nodes (4): @laravel/passkeys@^0.2.0, @simplewebauthn/browser@^13.3.0, @laravel/passkeys, @simplewebauthn/browser

### Community 157 - "package.json"
Cohesion: 0.22
Nodes (9): devDependencies, @alpinejs/collapse@^3.15.12, prettier@^3.9.6, prettier-plugin-blade@^3.2.2, prettier-plugin-tailwindcss@^0.8.1, @alpinejs/collapse, prettier, prettier-plugin-blade (+1 more)

### Community 158 - "resources/js"
Cohesion: 0.31
Nodes (9): addBlock(), addSlide(), addTab(), defaults(), duplicateBlock(), makeNestedBlock(), newId(), prepareBlock() (+1 more)

### Community 159 - "AGENTS.md"
Cohesion: 0.25
Nodes (7): CompanySettingService (logo / favicon upload handling), ContactNumberService::addAdditionalWhatsAppNumbers, EnvEditor (get / set, cyclomatic 6 and 5), ContactNumberService@buildContactNumbers (N+1 + Fat Method), ContentBlockCleanupService (extractFilePathsFromBlock, cleanupOrphanedUploads, cleanupAllTempFiles), Env rows on the sheet: locked by default, allowlist only, Complexity Hotspots Table (cyclomatic / lines)

### Community 162 - "database/migrations"
Cohesion: 0.29
Nodes (6): backfillAuthors(), {closure#1}(), {closure#2}(), {closure#3}(), {closure#4}(), up()

### Community 165 - "resources/views"
Cohesion: 0.25
Nodes (7): partials.cookie-consent, partials.site-footer, partials.site-nav, partials.whatsapp-fab, partials.cloak, partials.favicons, partials.theme-script

### Community 168 - "resources/js"
Cohesion: 0.25
Nodes (8): applyScrollLock(), focusDialog(), lockAdminContentScroll(), open(), openCreate(), openEdit(), popDialog(), pushDialog()

### Community 169 - "resources/js"
Cohesion: 0.25
Nodes (8): bootDayDesk(), deskClockText(), deskPad(), deskPrefersReduced(), deskRefresh(), items(), settle(), show()

### Community 170 - "resources/js"
Cohesion: 0.46
Nodes (7): bootSheet(), sheetElapsedText(), sheetInvalidText(), sheetList(), sheetMarksSentence(), sheetRefresh(), sheetValueOf()

### Community 172 - "composer.json"
Cohesion: 0.29
Nodes (7): pestphp/pest-plugin, php-http/discovery, config, allow-plugins, optimize-autoloader, preferred-install, sort-packages

### Community 173 - "package.json"
Cohesion: 0.29
Nodes (6): laravel-vite-plugin@^3.0.0, @tailwindcss/vite@^4.1.11, vite@^8.0.0, laravel-vite-plugin, @tailwindcss/vite, vite

### Community 174 - "package.json"
Cohesion: 0.29
Nodes (7): optionalDependencies, lightningcss-linux-x64-gnu@^1.29.1, @rollup/rollup-linux-x64-gnu@4.9.5, @tailwindcss/oxide-linux-x64-gnu@^4.0.1, lightningcss-linux-x64-gnu, @rollup/rollup-linux-x64-gnu, @tailwindcss/oxide-linux-x64-gnu

### Community 175 - "resources/js"
Cohesion: 0.43
Nodes (5): bind(), bindAll(), evaluate(), mark(), testers

### Community 178 - "composer.json"
Cohesion: 0.33
Nodes (6): autoload, files, psr-4, App\\, Database\\Factories\\, Database\\Seeders\\

### Community 180 - "database/migrations"
Cohesion: 0.33
Nodes (3): {closure#1}(), {closure#2}(), {closure#3}()

### Community 181 - "docker/entrypoint.sh"
Cohesion: 0.33
Nodes (5): RUN_QUEUE, RUN_REVERB, RUN_SCHEDULER, RUN_WEB, entrypoint.sh script

### Community 183 - "app/Enums"
Cohesion: 0.40
Nodes (4): Theme, Auto, Dark, Light

### Community 187 - "resources/views"
Cohesion: 0.40
Nodes (4): clearAll, clearCategory, clearTag, clearSearch

### Community 213 - "resources/views"
Cohesion: 0.40
Nodes (4): queueWarmup, admin.system-overview.card, measure, system-overview-packages

### Community 214 - "resources/js"
Cohesion: 0.50
Nodes (5): applyStatuses(), bulkSetStatus(), send(), setStatus(), toast()

### Community 215 - "resources/js"
Cohesion: 0.40
Nodes (5): destroyEditor(), removeBlock(), removeNestedBlock(), removeSlide(), removeTab()

### Community 216 - "resources/views"
Cohesion: 0.40
Nodes (4): admin.blog.posts.partials.basic-info, admin.blog.posts.partials.publish-settings, admin.blog.posts.partials.taxonomy, block-builder

### Community 217 - "resources/views"
Cohesion: 0.40
Nodes (4): admin.blog.posts.partials.basic-info, admin.blog.posts.partials.publish-settings, admin.blog.posts.partials.taxonomy, block-builder

### Community 245 - "resources/views"
Cohesion: 0.50
Nodes (3): gotoPage, nextPage, previousPage

### Community 246 - "resources/views"
Cohesion: 0.50
Nodes (3): nextPage, previousPage, setPage

### Community 247 - "resources/views"
Cohesion: 0.50
Nodes (3): nextPage, previousPage, setPage

### Community 248 - "resources/views"
Cohesion: 0.50
Nodes (3): gotoPage, nextPage, previousPage

### Community 252 - "package.json"
Cohesion: 0.67
Nodes (3): scripts, build, dev

## Ambiguous Edges - Review These
- `Tiered Docblock Conventions matrix (per layer: class, methods, @param, @return, @throws)` → `x-ui.* component library (button, input, field, card, link, alert-banner, ...)`  [AMBIGUOUS]
  DESIGN.md · relation: conceptually_related_to

## Knowledge Gaps
- **139 isolated node(s):** `Sidebar`, `Top`, `Chip`, `Crack`, `Shattered` (+134 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 2031 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **908 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `User` connect `Sign-in Auth Services` to `(root)`, `app/Models`, `Inspection Admin CRUD`, `System Overview Package Catalog`, `Roles & Permission Migrations`, `Quote Admin`, `Session Telemetry Enums`, `database/factories`, `Auth Form Requests`, `app/Models`, `Profile Update Requests`, `Admin Menu Builder`, `app/Services`, `Settings & Upload Services`, `User Session Model`, `Database Seeders`, `Eloquent Models`, `database/migrations`, `Media URL Accessors`, `Fortify User Creation`, `Password Policy Admin`, `Tag Admin`, `Application Version Model`, `Validation Rules & Requests`, `Application Version Admin`, `app/Http`, `app/Http`, `app/Support`, `app/Http`, `database/factories`, `app/Actions`, `app/Enums`, `app/Enums`, `app/Services`?**
  _High betweenness centrality (0.052) - this node is a cross-community bridge._
- **What connects `Sidebar`, `Top`, `Chip` to the rest of the system?**
  _139 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Admin JS Behaviors` be split into smaller, more focused modules?**
  _Cohesion score 0.031055900621118012 - nodes in this community are weakly interconnected._
- **Why does `SettingsManager` connect `Quote Email Notifications` to `app/Providers`, `app/Services`, `Public Site Controller`, `Settings & Upload Services`, `app/Services`, `Availability Calculation`, `User Session Model`, `Booking Availability Contracts`, `app/Services`, `Dashboard Service`, `SEO Static Route DTOs`, `Application Version Admin`, `Service Providers`, `app/Services`, `app/Services`, `app/Events`, `app/Actions`, `app/Services`, `app/Services`, `app/Http`, `app/Services`, `app/Services`, `app/Services`, `app/Models`, `app/Services`, `app/Services`?**
  _High betweenness centrality (0.033) - this node is a cross-community bridge._
- **Should `Booking Widget JS` be split into smaller, more focused modules?**
  _Cohesion score 0.06442307692307692 - nodes in this community are weakly interconnected._
- **Why does `Post` connect `SEO Sitemap & Posts` to `Quote Admin`, `Profile Update Requests`, `app/Models`, `app/Models`, `Eloquent Models`, `Media URL Accessors`, `Password Policy Requests`, `Tag Admin`, `Application Version Model`, `Orphaned Upload Cleanup`, `app/Services`, `app/Http`, `app/Livewire`, `app/Services`, `app/Http`, `app/Livewire`, `app/Models`, `app/Services`, `app/Services`?**
  _High betweenness centrality (0.032) - this node is a cross-community bridge._

### Low-confidence Hints
_AMBIGUOUS edges — the extractor was unsure. Verify before acting on these._

- **What is the exact relationship between `Tiered Docblock Conventions matrix (per layer: class, methods, @param, @return, @throws)` and `x-ui.* component library (button, input, field, card, link, alert-banner, ...)`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._