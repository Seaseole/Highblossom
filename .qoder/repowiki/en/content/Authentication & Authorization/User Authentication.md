# User Authentication

<cite>
**Referenced Files in This Document**   
- [fortify.php](file://config/fortify.php)
- [auth.php](file://config/auth.php)
- [session.php](file://config/session.php)
- [2026_10_03_130000_create_user_sessions_table.php](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php)
</cite>

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Analysis](#dependency-analysis)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)
10. [Appendices](#appendices)

## Introduction
This document explains the Highblossom user authentication system built on Laravel Fortify. It covers the configured authentication stack, session and remember-me behavior, rate limiting, two-factor and passkey features, password reset flows, registration controls, and the custom durable session ledger used to track concurrent sessions. The goal is to help developers understand how login, logout, registration, password management, and session lifecycle interact, and how to safely customize or extend the system.

## Project Structure
Authentication-related configuration and data structures are concentrated in:

- Configuration:
  - `config/fortify.php`: Fortify guard, username field, middleware, rate limiters, view routes, and enabled features.
  - `config/auth.php`: Default guard, Eloquent user provider, and password broker settings.
  - `config/session.php`: Session driver, lifetime, cookie security, SameSite policy, and persistence table.
- Database schema:
  - `database/migrations/2026_10_03_130000_create_user_sessions_table.php`: Durable `user_sessions` ledger for audit and concurrency tracking.

```mermaid
graph TB
    Fortify["Fortify<br/>Feature Router"] --> AuthConfig["Auth Config<br/>'web' guard + 'users' provider"]
    Fortify --> SessionConfig["Session Config<br/>'database' driver + cookie settings"]
    Fortify --> RateLimit["Rate Limiters<br/>'login' and 'two-factor'"]
    Fortify --> Features["Enabled Features<br/>registration, passwords,<br/>email verification, two-factor, passkeys"]
    AuthConfig --> UserModel["User Model<br/>(Eloquent provider)"]
    SessionConfig --> SessionTable["sessions (framework table)"]
    Fortify --> UserSessions["user_sessions (audit ledger)"]
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

## Core Components
This section summarizes the key configuration building blocks that drive the authentication flow.

### Fortify Configuration
- Guard and password broker:
  - Uses the `'web'` guard and `'users'` password broker.
- Username field:
  - Treats the `email` attribute as the primary login identifier.
- Lowercase usernames:
  - Enabled to normalize case before saving.
- Home redirect:
  - Redirects authenticated users to `/dashboard`.
- Route prefix/domain:
  - No prefix; domain is null by default.
- Middleware:
  - Routes use the `'web'` and `'registration.enabled'` middleware.
- Rate limiters:
  - Named `'login'` and `'two-factor'` limiters are registered.
- Views:
  - Built-in Fortify views are enabled.
- Features:
  - Registration, password resets, email verification, two-factor authentication with confirmation, and passkeys are enabled.

```mermaid
flowchart TD
    Start(["Request to Fortify route"]) --> MW["Apply 'web' and 'registration.enabled' middleware"]
    MW --> Feature{"Which feature?"}
    Feature -->|Login| LoginFlow["Use 'login' limiter<br/>Authenticate via 'web' guard"]
    Feature -->|Registration| RegFlow["Validate and create user<br/>Respect company registration setting"]
    Feature -->|Reset Password| ResetFlow["Send reset token<br/>Enforce throttle"]
    Feature -->|Two Factor| TwoFactorFlow["Challenge and confirm"]
    Feature -->|Passkeys| PasskeyFlow["Perform passkey flow"]
    LoginFlow --> Redirect["Redirect to '/dashboard'"]
    RegFlow --> Redirect
    ResetFlow --> Redirect
    TwoFactorFlow --> Redirect
    PasskeyFlow --> Redirect
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

### Authentication Stack
- Default guard:
  - `'web'` guard uses the session driver and `'users'` provider.
- User provider:
  - Eloquent provider backed by the application’s User model class.
- Password broker:
  - `'users'` broker points to the standard password reset tokens table.
- Password timeout:
  - Confirmation timeouts are configurable through environment variables.

```mermaid
graph LR
    Request["HTTP Request"] --> Guard["Guard 'web'<br/>(session)"]
    Guard --> Provider["Provider 'users'<br/>(eloquent)"]
    Provider --> Model["User Model Class"]
    Guard --> Session["Session Store"]
```

**Diagram sources**
- [auth.php:1-117](file://config/auth.php#L1-L117)

**Section sources**
- [auth.php:1-117](file://config/auth.php#L1-L117)

### Session and Remember-Me Behavior
- Driver:
  - Defaults to database-backed sessions.
- Lifetime:
  - Configurable via environment variable; defaults to a finite number of minutes.
- Cookie security:
  - Secure flag, HTTP-only, path, domain, and SameSite policies are configurable.
- Persistence:
  - Framework sessions persist into the configured session table.
- Concurrency:
  - A separate `user_sessions` ledger tracks device, IP, browser, login method, remember status, and lifecycle events.

```mermaid
erDiagram
  SESSIONS {
    string id PK
    string user_id
    string payload
    long last_activity
  }

  USER_SESSIONS {
    bigint id PK
    bigint user_id FK
    string session_id UK
    string ip_address
    string last_ip_address
    text user_agent
    string device_type
    string platform
    string browser
    string browser_version
    string login_method
    boolean remembered
    timestamp login_at
    timestamp last_seen_at
    timestamp ended_at
    string end_reason
    bigint revoked_by FK
    timestamp created_at
    timestamp updated_at
  }

  USERS ||--o{ USER_SESSIONS : "tracks"
  USERS ||--o{ SESSIONS : "owns framework session"
```

**Diagram sources**
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

**Section sources**
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

## Architecture Overview
The authentication architecture combines Fortify’s request-driven features with Laravel’s guard/provider/session infrastructure and an application-level session ledger.

```mermaid
sequenceDiagram
    participant Client as "Client"
    participant Fortify as "Fortify Controller"
    participant Limiter as "Rate Limiter"
    participant Guard as "'web' Guard"
    participant Provider as "'users' Provider"
    participant Model as "User Model"
    participant Session as "Session Store"
    participant Ledger as "user_sessions Ledger"

    Client->>Fortify: "POST /login"
    Fortify->>Limiter: "Check 'login' limiter"
    Limiter-->>Fortify: "Allowed or throttled"
    Fortify->>Guard: "Attempt login"
    Guard->>Provider: "Load user by 'email'"
    Provider->>Model: "Query user record"
    Model-->>Provider: "User instance"
    Provider-->>Guard: "Authenticated or not"
    alt "Success"
        Guard->>Session: "Persist session"
        Fortify->>Ledger: "Record login event"
        Fortify-->>Client: "Redirect to '/dashboard'"
    else "Failure"
        Fortify-->>Client: "Validation/authentication error"
    end
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

## Detailed Component Analysis

### Login Flow
- Identifier:
  - Login uses the `email` field as configured by Fortify.
- Rate limiting:
  - The `'login'` named limiter protects against brute-force attempts.
- Guard and provider:
  - Authentication goes through the `'web'` guard and the Eloquent `'users'` provider.
- Post-login:
  - Successful authentication redirects to `/dashboard`.
- Sessions:
  - Framework session storage persists the authenticated state.
  - The durable `user_sessions` ledger can be used to record device, IP, browser, and whether remember-me was used.

```mermaid
flowchart TD
    Start(["Login Request"]) --> CheckLimit["Check 'login' rate limiter"]
    CheckLimit --> Allowed{"Allowed?"}
    Allowed -->|No| Throttled["Return throttle response"]
    Allowed -->|Yes| Authenticate["Authenticate via 'web' guard"]
    Authenticate --> Valid{"Credentials valid?"}
    Valid -->|No| Error["Return authentication error"]
    Valid -->|Yes| CreateSession["Create session"]
    CreateSession --> RecordLedger["Record durable session entry"]
    RecordLedger --> Redirect["Redirect to '/dashboard'"]
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

### Logout Flow
- Logout terminates the current framework session.
- Application logic may also update the durable `user_sessions` ledger to mark sessions as ended.

```mermaid
sequenceDiagram
    participant Client as "Client"
    participant Fortify as "Fortify Controller"
    participant Guard as "'web' Guard"
    participant Session as "Session Store"
    participant Ledger as "user_sessions Ledger"

    Client->>Fortify: "POST /logout"
    Fortify->>Guard: "Logout"
    Guard->>Session: "Flush session"
    Fortify->>Ledger: "Mark session ended"
    Fortify-->>Client: "Redirect to home or login page"
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

### Registration Flow
- Registration is enabled via Fortify features.
- The `'registration.enabled'` middleware short-circuits the `/register` route based on the application’s company settings while other Fortify routes bypass this check.
- After successful registration, users are redirected according to Fortify’s home configuration.

```mermaid
flowchart TD
    Start(["GET /register"]) --> MW["Apply 'registration.enabled' middleware"]
    MW --> Allowed{"Registration allowed?"}
    Allowed -->|No| Blocked["Block access"]
    Allowed -->|Yes| Submit["Submit registration form"]
    Submit --> Validate["Validate input"]
    Validate --> Create["Create user via Fortify action"]
    Create --> Auth["Automatically authenticate if configured"]
    Auth --> Redirect["Redirect to '/dashboard'"]
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

### Password Management
- Password reset requests are protected by the password broker and its throttle configuration.
- Fortify enables the reset-password feature, which coordinates token generation, validation, and updating the user’s password.
- Two-factor and passkey features are also enabled, adding additional layers of identity assurance.

```mermaid
flowchart TD
    Start(["Password Reset Request"]) --> Broker["Password Broker"]
    Broker --> Throttle["Throttle reset attempts"]
    Throttle --> Token["Generate reset token"]
    Token --> Deliver["Deliver reset link"]
    Deliver --> Validate["Validate token and new password"]
    Validate --> Update["Update password"]
    Update --> Done(["Complete"])
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)

### Two-Factor Authentication and Passkeys
- Two-factor authentication is enabled with confirmation and password confirmation enabled.
- Passkeys are enabled without requiring password confirmation during setup.
- These features integrate with Fortify’s controllers and views.

```mermaid
graph LR
    Login["Login"] --> TFA["Two-Factor Challenge"]
    TFA --> Confirm["Confirm Identity"]
    Confirm --> Passkey["Optional Passkey Setup"]
    Passkey --> Dashboard["Dashboard"]
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)

### Customizing Authentication Behavior
Common customization patterns include:

- Changing the login identifier:
  - Adjust the Fortify username configuration when the application uses a different field.
- Adding new login fields:
  - Extend Fortify’s login handling by overriding actions or registering custom request handlers.
- Custom validation rules:
  - Apply application-specific validation in form requests or custom rules before creating users or updating profiles.
- Custom responses:
  - Replace Fortify’s default responses to return JSON, API-friendly payloads, or custom redirects.

These customizations should preserve existing security controls such as rate limiting, password hashing, CSRF protection, and session security.

[No sources needed since this section provides general guidance]

## Dependency Analysis
The following diagram maps the main dependencies between authentication configuration components.

```mermaid
graph TB
    FortifyCfg["config/fortify.php"] --> WebGuard["'web' guard"]
    FortifyCfg --> PasswordBroker["'users' password broker"]
    FortifyCfg --> RateLimiters["'login', 'two-factor' limiters"]
    FortifyCfg --> Features["registration, passwords,<br/>email verification, two-factor, passkeys"]
    AuthCfg["config/auth.php"] --> UserProvider["'users' Eloquent provider"]
    SessionCfg["config/session.php"] --> SessionStore["Database session store"]
    LedgerSchema["user_sessions migration"] --> AuditLedger["Durable session ledger"]
```

**Diagram sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

## Performance Considerations
- Use a fast session backend:
  - For high traffic, consider Redis or another cache-backed session store instead of the database.
- Keep rate limits conservative:
  - Tune the `'login'` and `'two-factor'` limiters to balance security and usability.
- Minimize unnecessary writes:
  - Avoid logging excessive details on every request; batch or sample session ledger updates where appropriate.
- Indexing:
  - Ensure database indexes support frequent queries on session tables and the `user_sessions` ledger.
- View rendering:
  - When using Fortify views, ensure assets are precompiled and cached.

[No sources needed since this section provides general guidance]

## Troubleshooting Guide
Common issues and diagnostics:

- Cannot log in:
  - Verify the configured username field matches the expected input.
  - Check that the `'web'` guard and `'users'` provider are correctly configured.
- Registration is blocked:
  - Inspect the `'registration.enabled'` middleware behavior and the company setting controlling registration.
- Too many login attempts:
  - Review the `'login'` rate limiter configuration and temporary lockout windows.
- Password reset emails not received:
  - Confirm mail configuration and password broker throttle settings.
- Session expiring too quickly:
  - Adjust session lifetime and cookie secure flags.
- Concurrent session confusion:
  - Inspect the `user_sessions` ledger to correlate framework sessions with device/browser metadata.

**Section sources**
- [fortify.php:1-162](file://config/fortify.php#L1-L162)
- [auth.php:1-117](file://config/auth.php#L1-L117)
- [session.php:1-200](file://config/session.php#L1-L200)
- [2026_10_03_130000_create_user_sessions_table.php:1-67](file://database/migrations/2026_10_03_130000_create_user_sessions_table.php#L1-L67)

## Conclusion
Highblossom’s authentication system centers on Laravel Fortify, the `'web'` guard with an Eloquent user provider, and database-backed sessions. Registration is gated by a custom middleware, while login, password reset, two-factor authentication, and passkeys are enabled through Fortify features. A durable `user_sessions` ledger complements framework sessions by providing an auditable history of device, IP, browser, login method, and lifecycle events. When customizing authentication, preserve rate limiting, secure session cookies, and proper password handling.

[No sources needed since this section summarizes without analyzing specific files]

## Appendices

### Security Checklist
- Enforce HTTPS and secure session cookies in production.
- Keep rate limiters active for login and two-factor challenges.
- Require strong passwords and enable two-factor authentication where appropriate.
- Monitor the `user_sessions` ledger for suspicious activity.
- Rotate secrets and keep the application encryption key secure.

[No sources needed since this section provides general guidance]