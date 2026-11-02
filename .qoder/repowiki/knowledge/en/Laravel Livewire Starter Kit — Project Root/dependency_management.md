## Approach

The project uses two independent package managers:

- **PHP**: Composer for the Laravel backend and its third-party packages.
- **JavaScript/Build tools**: npm (with Vite) for frontend assets, Tailwind CSS, Alpine.js, CKEditor, etc.

There is no vendoring of PHP dependencies; `composer.lock` pins exact versions and `vendor/` is expected to be generated at install time. There is also no JS vendoring — `package-lock.json` pins exact versions and `node_modules/` is generated locally.

A local in-repo package (`packages/ContentBlocks`) is treated as a dependency via a Composer `path` repository, so it ships alongside the app without being published to Packagist.

## Key Files

- `composer.json` — root PHP dependency manifest, scripts, autoload, and repo config.
- `composer.lock` — pinned PHP dependency tree.
- `package.json` — npm dependency manifest (private, ESM).
- `package-lock.json` — pinned JS dependency tree.
- `.npmrc` — sets `ignore-scripts=true` globally for npm installs.
- `packages/ContentBlocks/composer.json` — standalone library manifest declaring itself as a Laravel package provider.
- `vite.config.js` — declares build-time JS dependencies (Tailwind plugin, Laravel Vite plugin).

## Architecture and Conventions

### Composer

- Root package name: `laravel/livewire-starter-kit`, type `project`, requires `php ^8.2`.
- A `repositories` entry registers `packages/ContentBlocks` as a `path` repository with `symlink: true`, so the app consumes it directly from disk rather than through Packagist.
- The root app depends on it via `"highblossom/content-blocks": "@dev"`.
- `packages/ContentBlocks` is declared as `type: library` with PSR-4 namespace `Highblossom\ContentBlocks\`, lists `laravel/framework ^13.0` as its only runtime requirement, and registers its service provider under `extra.laravel.providers` so Laravel auto-discovers it.
- Autoloading conventions: `App\\` → `app/`, `Database\\Factories\\` → `database/factories/`, `Database\\Seeders\\` → `database/seeders/`; helper functions are loaded via the `files` key from `app/Support/helpers.php`. Tests map `Tests\\` → `tests/`.
- Composer scripts orchestrate setup (`setup`, `dev`, `test`, `lint`, `ci:check`) and run standard Laravel post-hooks (`post-autoload-dump`, `post-update-cmd`, `post-root-package-install`, `post-create-project-cmd`, `pre-package-uninstall`).
- `config.sort-packages = true` keeps manifests ordered; `minimum-stability = stable` with `prefer-stable = true` restricts releases unless explicitly overridden by a version constraint (e.g., `@dev` for the local package). `optimize-autoloader = true`.
- Only two Composer plugins are allowed: `pestphp/pest-plugin` and `php-http/discovery` via `allow-plugins`.

### npm / Vite

- `package.json` is `private: true` with `type: module` (ESM).
- Runtime dependencies include Tailwind CSS v4 (`@tailwindcss/vite`), Alpine.js, Bootstrap 4, jQuery, Popper.js, CKEditor Classic build, `@laravel/passkeys`, and `@simplewebauthn/browser`.
- Build tooling (Vite, autoprefixer, laravel-vite-plugin) lives under `dependencies` rather than `devDependencies` because they are invoked by `npm run build` in production-oriented scripts.
- Optional platform-specific binaries are isolated under `optionalDependencies` (`@rollup/rollup-linux-x64-gnu`, `@tailwindcss/oxide-linux-x64-gnu`, `lightningcss-linux-x64-gnu`) to avoid failing installs on other platforms.
- Prettier and Blade/Tailwind plugins are separated into `devDependencies`.
- `.npmrc` enforces `ignore-scripts=true`, which suppresses postinstall hooks across all npm operations.
- Scripts are minimal: `build` delegates to Vite; `dev` delegates to Vite's dev server.

## Constraints

- PHP dependencies must be declared in `composer.json`; lockfile `composer.lock` is committed and authoritative for reproducible installs.
- JS dependencies must be declared in `package.json`; lockfile `package-lock.json` is committed and authoritative.
- The local `packages/ContentBlocks` library is consumed exclusively through the Composer `path` repository — adding it as a separate Git submodule or remote packagist reference would bypass the symlink mechanism.
- No private registry is configured for either Composer or npm; both resolve against their default repositories (Packagist and the npm registry).
- New Composer plugins require explicit approval in `config.allow-plugins`; unlisted plugins will fail installation.