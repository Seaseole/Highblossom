# CI/CD Pipeline Configuration

<cite>
**Referenced Files in This Document**
- [lint.yml](file://.github/workflows/lint.yml)
- [composer.json](file://composer.json)
- [composer.lock](file://composer.lock)
- [pint.json](file://pint.json)
- [package.json](file://package.json)
</cite>

## Update Summary
**Changes Made**
- Added guidance for handling `composer.lock` content-hash changes and vendor tree reinstallation.
- Updated the dependency installation section to explain when `composer install` must be run after pulling lockfile updates.
- Added a dedicated lockfile change workflow section with practical examples.
- Extended troubleshooting guidance to cover stale vendor trees, Composer cache invalidation, and CI failures caused by outdated local dependency trees.

## Table of Contents
1. [Introduction](#introduction)
2. [Project Structure](#project-structure)
3. [Core Components](#core-components)
4. [Architecture Overview](#architecture-overview)
5. [Detailed Component Analysis](#detailed-component-analysis)
6. [Dependency Lock Management](#dependency-lock-management)
7. [Performance Considerations](#performance-considerations)
8. [Troubleshooting Guide](#troubleshooting-guide)
9. [Conclusion](#conclusion)
10. [Appendices](#appendices)

## Introduction
This document explains the Highblossom application’s continuous integration and delivery setup. It focuses on the existing GitHub Actions linting pipeline, how PHP code style checks with Pint are executed, how dependencies are installed, and how to extend the workflow with testing, asset building, security scanning, and deployment automation. It also covers environment variables, secrets handling, conditional execution based on branch triggers, and practical examples for adding database migration validation, integration tests, and artifact generation.

The repository is a Laravel + Livewire application that uses Composer for PHP dependencies, Pint for PHP code formatting, Vite for frontend assets, and PHPUnit for unit and integration tests. The current automated quality check runs only through a dedicated linter workflow.

## Project Structure
The CI/CD-related configuration is primarily located under `.github/workflows`, while build and tooling commands live in `composer.json` and `package.json`. Code style rules for Pint are defined in `pint.json`. The `composer.lock` file pins the exact resolved dependency graph used by the project.

```mermaid
graph TB
  subgraph "Repository"
    GH[".github/workflows/lint.yml"]
    COMPOSER["composer.json"]
    LOCK["composer.lock"]
    PINT["pint.json"]
    NPM["package.json"]
  end

  subgraph "GitHub Actions Runner"
    CHECKOUT["Checkout code"]
    SETUP_PHP["Setup PHP 8.4"]
    DEPS["Install Composer and npm dependencies"]
    LINT["Run Pint"]
  end

  GH --> CHECKOUT --> SETUP_PHP --> DEPS --> LINT
  LINT --> COMPOSER
  LINT --> PINT
  DEPS --> COMPOSER
  DEPS --> LOCK
  DEPS --> NPM
```

**Diagram sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)
- [composer.json:1-138](file://composer.json#L1-L138)
- [composer.lock:1-20](file://composer.lock#L1-L20)
- [pint.json:1-22](file://pint.json#L1-L22)
- [package.json:1-35](file://package.json#L1-L35)

**Section sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)
- [composer.json:1-138](file://composer.json#L1-L138)
- [composer.lock:1-20](file://composer.lock#L1-L20)
- [pint.json:1-22](file://pint.json#L1-L22)
- [package.json:1-35](file://package.json#L1-L35)

## Core Components
- GitHub Actions workflow: `.github/workflows/lint.yml` defines the linter job, branch triggers, environment usage, and step sequence.
- PHP tooling: `composer.json` contains scripts for dependency installation, Pint execution, test orchestration, and development tasks.
- Dependency lock: `composer.lock` records the locked dependency graph and its content hash.
- Code style configuration: `pint.json` configures Pint presets and PHPDoc rules.
- Frontend tooling: `package.json` defines Vite build and development scripts used by the application.

Key responsibilities:
- Branch-based triggering for push and pull request events.
- PHP 8.4 runtime provisioning.
- Conditional authentication for private Composer repositories using repository secrets.
- Installation of PHP and Node.js dependencies against a locked dependency graph.
- Running Pint in parallel mode as a quality gate.

**Section sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)
- [composer.json:1-138](file://composer.json#L1-L138)
- [composer.lock:1-20](file://composer.lock#L1-L20)
- [pint.json:1-22](file://pint.json#L1-L22)
- [package.json:1-35](file://package.json#L1-L35)

## Architecture Overview
The current CI architecture is intentionally minimal. A single job performs repository checkout, PHP environment setup, dependency installation, and code style enforcement. Secrets are used only to authenticate access to the Flux UI Composer repository.

```mermaid
sequenceDiagram
  participant GitHub as "GitHub Events"
  participant Runner as "Ubuntu Runner"
  participant Actions as "Actions"
  participant Composer as "Composer Cache"
  participant NPM as "npm Cache"

  GitHub->>Runner: Trigger on push or pull request
  Runner->>Actions: Checkout repository
  Runner->>Actions: Setup PHP 8.4
  Runner->>Actions: Configure private Composer credentials
  Runner->>Composer: Install PHP dependencies from composer.lock
  Runner->>NPM: Install Node.js dependencies
  Runner->>Actions: Run Pint linting
  Actions-->>GitHub: Job status result
```

**Diagram sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)

## Detailed Component Analysis

### Existing Linter Workflow
The workflow is named `linter` and is triggered by pushes and pull requests targeting specific branches: `develop`, `main`, `master`, and `workos`. The `quality` job runs on an Ubuntu runner, uses the `Testing` GitHub environment, and executes four main steps:

1. Checkout the repository.
2. Set up PHP 8.4.
3. Add credentials for the Flux UI Composer repository using repository secrets.
4. Install dependencies via Composer and npm.
5. Execute Pint through the Composer script.

Branch trigger behavior:
- Pushes to any of the configured branches run the workflow.
- Pull requests targeting any of the configured branches run the workflow.

Environment usage:
- The job references `environment: Testing`, which allows repository-scoped environment protection rules and environment-specific variables/secrets.

Permissions:
- The workflow declares `contents: write`, enabling Git operations such as commits if needed. In the current file, commit auto-fix is commented out, so no changes are pushed.

Secrets usage:
- `secrets.FLUX_USERNAME` and `secrets.FLUX_LICENSE_KEY` are consumed to configure HTTP basic authentication for the Flux UI Composer repository before dependency installation.

```mermaid
flowchart TD
  Start(["Workflow Trigger"]) --> BranchCheck{"Branch matches<br/>develop, main, master, workos?"}
  BranchCheck -->|Yes| QualityJob["Start 'quality' job"]
  BranchCheck -->|No| Skip["Skip workflow"]

  QualityJob --> Checkout["Checkout code"]
  Checkout --> SetupPHP["Setup PHP 8.4"]
  SetupPHP --> AddFluxCreds["Add Flux credentials from secrets"]
  AddFluxCreds --> InstallDeps["Install Composer and npm dependencies"]
  InstallDeps --> RunPint["Run Pint"]
  RunPint --> End(["Job Complete"])
```

**Diagram sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)

**Section sources**
- [lint.yml:1-50](file://.github/workflows/lint.yml#L1-L50)

### PHP Linting with Pint
Pint is configured through `pint.json` and invoked via Composer scripts:

- `composer lint`: Runs Pint in parallel mode.
- `composer lint:check`: Runs Pint in test mode, failing the build when formatting issues are found.
- `composer test`: Clears configuration, runs Pint checks, then executes tests.

The workflow currently runs `composer lint`, which formats code rather than strictly validating it. If strict validation is required, use the `lint:check` script in future workflow jobs.

Pint configuration highlights:
- Uses the Laravel preset.
- Enforces several PHPDoc rules, including alignment, separation, summary presence, trimming, scalar type annotations, tag casing, and return self-reference consistency.

```mermaid
flowchart TD
  Entry["CI Step: composer lint"] --> Parallel["Run Pint in parallel"]
  Parallel --> Rules["Apply Laravel preset and custom PHPDoc rules"]
  Rules --> Output["Update source files according to formatter rules"]
  Output --> Exit["Exit with success"]
```

**Diagram sources**
- [lint.yml:30-45](file://.github/workflows/lint.yml#L30-L45)
- [composer.json:88-93](file://composer.json#L88-L93)
- [pint.json:1-22](file://pint.json#L1-L22)

**Section sources**
- [composer.json:88-101](file://composer.json#L88-L101)
- [pint.json:1-22](file://pint.json#L1-L22)
- [lint.yml:30-45](file://.github/workflows/lint.yml#L30-L45)

### Dependency Installation
The workflow installs both PHP and Node.js dependencies:

- Composer installation uses quiet mode, disables ANSI output, interactions, scripts, progress indicators, and prefers prebuilt distributions.
- npm installation is performed after Composer.
- Private package access for Flux UI is enabled through Composer HTTP basic authentication using repository secrets.

Important notes:
- Composer scripts are disabled during dependency installation to avoid side effects in CI.
- The `Testing` environment can be used to scope environment variables and secrets.
- Caching Composer and npm artifacts can improve performance in later workflow versions.

```mermaid
flowchart TD
  Start["Install Dependencies"] --> Auth["Configure Flux credentials from secrets"]
  Auth --> ComposerInstall["composer install with safe CI flags"]
  ComposerInstall --> NpmInstall["npm install"]
  NpmInstall --> Done["Dependencies ready"]
```

**Diagram sources**
- [lint.yml:20-45](file://.github/workflows/lint.yml#L20-L45)
- [composer.json:12-23](file://composer.json#L12-L23)

**Section sources**
- [lint.yml:20-45](file://.github/workflows/lint.yml#L20-L45)
- [composer.json:12-23](file://composer.json#L12-L23)

### Frontend Asset Building
Frontend assets are built using Vite through `package.json`. The current linter workflow does not build assets; it only installs them. To add asset building to CI:

- Add a dedicated job or step that runs the Vite build command.
- Use the same node version strategy as your local development or Docker environment.
- Cache npm dependencies to speed up repeated builds.
- Optionally generate build artifacts for later deployment stages.

Recommended addition:
- Introduce a `build-assets` job that runs `npm ci` followed by `npm run build`.
- Publish compiled assets as workflow artifacts if they are needed by downstream jobs.

**Section sources**
- [package.json:1-35](file://package.json#L1-L35)

### Environment Variables and Secrets Handling
Current secret usage:
- `FLUX_USERNAME` and `FLUX_LICENSE_KEY` are read from GitHub repository secrets and passed into Composer configuration for accessing the Flux UI private repository.

Environment usage:
- The job sets `environment: Testing`, allowing environment-scoped variables and secrets.
- For additional needs such as database URLs, mail settings, cache drivers, or third-party API keys, define those variables under the `Testing` environment in GitHub Settings.

Best practices:
- Keep sensitive values in GitHub Secrets or environment-scoped secrets.
- Avoid printing secrets in logs.
- Restrict access to protected environments where necessary.
- Use distinct environments for staging and production deployments.

**Section sources**
- [lint.yml:18-45](file://.github/workflows/lint.yml#L18-L45)

### Conditional Execution Based on Branch Triggers
The workflow is explicitly configured to run only for:
- Push events on `develop`, `main`, `master`, and `workos`.
- Pull request events targeting the same branches.

If you need different behavior per branch:
- Use `if` conditions on jobs or steps.
- Example patterns include skipping linting on documentation-only changes, running deployment jobs only on `main` or `master`, or gating security scans to pull requests.

Example concepts:
- Deploy only on push to `main` or `master`.
- Skip expensive jobs for small pull requests or non-code changes.
- Run different test suites depending on changed directories.

**Section sources**
- [lint.yml:3-16](file://.github/workflows/lint.yml#L3-L16)

## Dependency Lock Management
The `composer.lock` file locks the exact dependency graph used by the application. Its `content-hash` value identifies the resolved dependency state. When this hash changes, the pinned dependency set has changed, and the `vendor` directory must be reinstalled to match the new lockfile.

In this repository, the dependency lock was updated to version 13.39.0, reflected by a changed `composer.lock` content-hash. After pulling this lockfile change, developers and CI runners should reinstall dependencies rather than assuming the existing `vendor` tree is valid.

Recommended operational rules:
- Treat a changed `composer.lock` content-hash as a signal that `composer install` or `composer update` must be executed.
- Do not reuse a stale `vendor` directory across lockfile updates.
- Prefer `composer install --no-scripts` in CI to install exactly what is locked without running post-install hooks unnecessarily.
- Run `composer update` only when changing version constraints in `composer.json`; otherwise, prefer committing an updated `composer.lock` generated by `composer update --lock` or equivalent tooling.
- Reinstall dependencies whenever CI reports missing classes, autoload errors, or unexpected behavior after a lockfile update.

```mermaid
flowchart TD
  Start["Pull Changes"] --> CheckLock{"composer.lock content-hash changed?"}
  CheckLock -->|Yes| Reinstall["Run composer install or composer update"]
  CheckLock -->|No| Validate["Validate existing vendor tree"]
  Reinstall --> Validate
  Validate --> Continue["Continue linting, testing, or deployment"]
```

**Diagram sources**
- [composer.lock:1-20](file://composer.lock#L1-L20)
- [composer.json:1-23](file://composer.json#L1-L23)
- [lint.yml:20-45](file://.github/workflows/lint.yml#L20-L45)

**Updated** Added explicit guidance for handling Composer lockfile content-hash changes and vendor tree reinstallation.

**Section sources**
- [composer.lock:1-20](file://composer.lock#L1-L20)
- [composer.json:1-23](file://composer.json#L1-L23)
- [lint.yml:20-45](file://.github/workflows/lint.yml#L20-L45)

## Performance Considerations
- Prefer `composer install --no-scripts` in CI, as already implemented, to avoid unnecessary post-install hooks.
- Enable Composer and npm dependency caching in expanded workflows to reduce install time.
- Run Pint in parallel mode, as configured in the Composer script.
- Separate linting, testing, and building into parallel jobs where possible.
- Use matrix strategies for testing across multiple PHP or Node.js versions if required.
- Cache compiled assets separately from dependencies to minimize rebuild costs.
- Invalidate Composer caches only when the `composer.lock` content-hash changes or when dependency resolution problems occur.

[No sources needed since this section provides general guidance]

## Troubleshooting Guide
Common issues and resolutions:

- Private Composer repository authentication fails:
  - Ensure `FLUX_USERNAME` and `FLUX_LICENSE_KEY` are set in GitHub Secrets.
  - Verify the `Testing` environment has access to these secrets.
  - Confirm the Composer repository URL matches the authenticated endpoint.

- Lint job passes but code style still differs locally:
  - The workflow runs `composer lint`, which formats code. If strict validation is desired, switch to `composer lint:check`.
  - Run `composer lint` locally to apply fixes automatically.

- Dependency installation is slow:
  - Add Composer and npm caching steps.
  - Use `npm ci` instead of `npm install` for deterministic installs.
  - Pin Node.js and PHP versions consistently across local, CI, and production environments.

- Tests fail due to missing environment configuration:
  - Create a `.env.testing` file or configure environment variables in the `Testing` environment.
  - Provide database credentials, cache configuration, and mailer settings as needed.

- Database migrations cannot run in CI:
  - Provision a MySQL or SQLite service container.
  - Run migrations before executing tests.
  - Seed test data only when required by integration tests.

- Vendor tree becomes stale after `composer.lock` content-hash changes:
  - Delete or ignore the existing `vendor` directory and run `composer install` again.
  - In CI, rely on the workflow’s `composer install` step, which reinstalls dependencies against the committed lockfile.
  - If Composer cache corruption is suspected, clear Composer cache and retry the install.
  - Avoid sharing cached `vendor` directories across lockfile updates unless the cache key includes the lockfile content-hash.

- CI fails after updating dependencies:
  - Verify whether `composer.json` version constraints were changed; if so, regenerate `composer.lock` intentionally.
  - Compare the `composer.lock` content-hash between branches to identify when the dependency graph changed.
  - Run `composer validate` and inspect Composer audit output for vulnerable or incompatible packages.

**Section sources**
- [lint.yml:18-45](file://.github/workflows/lint.yml#L18-L45)
- [composer.json:88-101](file://composer.json#L88-L101)
- [composer.lock:1-20](file://composer.lock#L1-L20)

## Conclusion
The Highblossom application currently has a focused linter workflow that enforces PHP code style using Pint on push and pull request events for selected branches. It installs Composer and npm dependencies, authenticates to a private Composer repository using secrets, and runs Pint in parallel. Because the dependency lock has been updated to version 13.39.0, the `composer.lock` content-hash has changed, and the `vendor` directory must be reinstalled after pulling the lockfile update. Extending this pipeline requires adding jobs for unit and integration testing, asset building, security scanning, and deployment automation. The recommended approach is to preserve the existing linter job, introduce separate jobs for testing and building, manage Composer lockfile changes carefully, and use GitHub environments and secrets to manage sensitive configuration safely.

[No sources needed since this section summarizes without analyzing specific files]

## Appendices

### Extending the Pipeline: Recommended Jobs
- Unit and integration tests:
  - Add a `tests` job that provisions a database service, installs dependencies, runs migrations, seeds fixtures if needed, and executes PHPUnit.
  - Use `composer test` or `php artisan test` as appropriate.
  - Generate JUnit-style test reports for better visibility in pull requests.

- Asset building:
  - Add a `build-assets` job that runs `npm ci` and `npm run build`.
  - Cache npm dependencies and generated assets.
  - Publish compiled assets as workflow artifacts for deployment jobs.

- Security scanning:
  - Add a `security` job that runs static analysis, dependency vulnerability scanning, and container image scanning if applicable.
  - Fail the pipeline on critical vulnerabilities.

- Deployment automation:
  - Add a `deploy-staging` job triggered by push to `main` or `master`.
  - Add a `deploy-production` job gated by manual approval and protected branches.
  - Use GitHub environments for deployment approvals and environment-specific secrets.

### Adding Database Migration Testing
Recommended flow:
1. Start a MySQL or SQLite service container.
2. Install dependencies.
3. Copy `.env.testing` or export required variables.
4. Run database migrations.
5. Run seeders only for tests that require seeded data.
6. Execute integration tests.

```mermaid
flowchart TD
  Start["Tests Job"] --> Service["Start database service"]
  Service --> Install["Install dependencies"]
  Install --> Migrate["Run database migrations"]
  Migrate --> Seed["Seed test data (optional)"]
  Seed --> RunTests["Run integration tests"]
  RunTests --> Report["Generate test reports"]
```

### Adding Integration Tests
Integration tests should exercise endpoints, queues, mail sending, storage access, and external integrations. Recommended steps:
- Isolate tests from production state.
- Use in-memory or temporary databases where possible.
- Mock third-party services unless real integration is required.
- Capture logs and screenshots for failed UI tests.

### Artifact Generation
Useful CI artifacts include:
- Compiled frontend assets.
- Test report XML files.
- Coverage reports.
- Build metadata such as commit SHA and timestamp.
- Deployment bundles or Docker images.

Publishing artifacts:
- Use GitHub Actions upload/download actions.
- Restrict artifact retention policies.
- Avoid uploading large binary files unnecessarily.

### Managing Composer Lockfile Updates in CI
Recommended practices:
- Always commit updated `composer.lock` files when dependency versions change.
- Treat a changed `composer.lock` content-hash as a breaking change for cached dependency trees.
- Avoid relying on shared `vendor` directories across lockfile updates.
- In CI, keep the existing `composer install` step; it reinstalls dependencies against the committed lockfile.
- Add a lint or validation step that warns contributors when `composer.json` changes without a corresponding `composer.lock` update.

```mermaid
flowchart TD
  PR["Pull Request"] --> DetectLock["Detect composer.lock change"]
  DetectLock -->|Changed| RequireReinstall["Require full composer install"]
  DetectLock -->|Unchanged| ValidateTree["Validate existing vendor tree"]
  RequireReinstall --> RunPipeline["Run lint, tests, and build"]
  ValidateTree --> RunPipeline
```

### Example Workflow Extensions
Conceptual additions you can implement in a new workflow file:
- A `ci-full` workflow that orchestrates linting, testing, building, security scanning, and deployment.
- A `deploy` workflow that runs only on protected branches and requires manual approval.
- A `nightly` scheduled workflow for long-running tests and security audits.
- A lockfile-aware validation job that detects `composer.lock` content-hash changes and forces dependency reinstallation.

[No sources needed since this section provides conceptual guidance]