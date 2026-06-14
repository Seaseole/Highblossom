# Highblossom Project

Highblossom is a Laravel-based project built as a starter kit leveraging Livewire. It features a custom `ContentBlocks` package and integrates various administration and security tools.

## Project Overview

- **Framework**: Laravel 13
- **Frontend Stack**: Livewire 4, Alpine.js, Tailwind CSS (v4), and Vite.
- **Admin Panel**: Leverages `jeroennoten/laravel-adminlte` and custom Livewire components for content management.
- **Security & Authentication**: Laravel Fortify, WebAuthn/Passkeys for secure authentication.
- **Content Management**: Features a bespoke `ContentBlocks` package (located in `packages/ContentBlocks`) for dynamic page building.

## Development & Operations

The project includes pre-defined scripts in `composer.json` to streamline development.

### Setup
To initialize the project, run:
```bash
composer setup
```

### Development
To start the development environment (Server, Queue, Logs, and Vite):
```bash
composer dev
```

### Testing & Linting
- **Linting**: Run `composer lint` to fix code style via Pint.
- **Test**: Run `composer test` to run tests and verify linting.

## Development Conventions

- **Architecture**: Follows standard Laravel structure (`app/` for logic, `resources/` for UI).
- **Component Design**: Uses Livewire components for interactive UI elements. Client-side state is primarily managed using Alpine.js.
- **Code Style**: Uses Laravel Pint for automated code styling.
- **Packages**: Custom packages are developed in the `packages/` directory, following a PSR-4 autoloading structure.
