## Approach

The repository manages dependencies through two independent package managers:

- **PHP**: Composer (PSR-4 autoload, `composer.json` + `composer.lock`).
- **Frontend assets**: npm / Vite (`package.json` + `package-lock.json`).

There is no vendoring of PHP packages into a checked-in `vendor/` directory — only the lockfile is committed. Frontend assets are built by Vite and not vendored either.

## Key Files

- `composer.json` — root PHP dependency manifest, scripts, autoload, and composer config.
- `composer.lock` — pinned PHP dependency tree.
- `package.json` — frontend dependency manifest (npm).
- `package-lock.json` — pinned frontend dependency tree.
- `.npmrc` — npm configuration (`ignore-scripts=true`).
- `packages/ContentBlocks/composer.json` — local Laravel package definition for the in-repo content-blocks library.
- `vite.config.js` — Vite build pipeline consuming the npm dependencies.

## Architecture & Conventions

### PHP dependencies (`composer.json`)

- Root project type is `project`, not `library`.
- Dependencies are split between `require` (runtime) and `require-dev` (testing/linting tooling such as Pint, Pail, Sail, PHPUnit, Mockery, Debugbar, Truss).
- PSR-4 autoloading maps `App\` → `app/`, `Database\Factories\` → `database/factories/`, `Database\Seeders\` → `database/seeders/`; an additional file autoloader pulls in `app/Support/helpers.php`.
- `autoload-dev` maps `Tests\` → `tests/`.
- Scripts orchestrate the full workflow: `setup` runs `composer install`, env copy, key generation, migration, then `npm install` and `npm run build`; `dev` concurrently boots the server, queue listener, Pail logs, and Vite dev server; `test` clears config, runs Pint in check mode, then `php artisan test`.
- Composer hooks: `post-autoload-dump` runs `artisan package:discover`, `post-update-cmd` publishes Laravel assets, `post-root-package-install` copies `.env.example`, `post-create-project-cmd` generates the key and seeds SQLite.
- `config.optimize-autoloader = true`, `preferred-install = "dist"`, `sort-packages = true`, `minimum-stability = "stable"`, `prefer-stable = true`.
- `allow-plugins` explicitly whitelists `pestphp/pest-plugin` and `php-http/discovery`.

### Local path package

A sibling directory `packages/ContentBlocks` defines a separate `composer.json` with name `highblossom/content-blocks`. The root `composer.json` registers it via a `path` repository pointing at `packages/ContentBlocks` with `options.symlink = true`, so Composer installs it as a symlinked dependency. This is how the app consumes its own internal package.

### Frontend dependencies (`package.json`)

- Declared under `dependencies` (runtime): CKEditor, AlpineJS, Tailwind CSS v4, Vite, Bootstrap, jQuery, Popper.js, `@laravel/passkeys`, `@simplewebauthn/browser`, `@tailwindcss/vite`, `laravel-vite-plugin`, autoprefixer.
- `optionalDependencies` pins Linux-specific native binaries (`@rollup/rollup-linux-x64-gnu`, `@tailwindcss/oxide-linux-x64-gnu`, `lightningcss-linux-x64-gnu`) to avoid unnecessary downloads on other platforms.
- `devDependencies` holds Prettier and Blade/Tailwind plugins.
- Scripts are minimal: `build` delegates to `vite build`, `dev` to `vite`.

### npm registry behavior

`.npmrc` sets `ignore-scripts=true`, which suppresses execution of postinstall scripts for all npm packages — a security posture applied repo-wide.

### Lockfiles

Both `composer.lock` and `package-lock.json` are present, pinning exact transitive versions for reproducible builds. No private registries or authentication tokens are configured in these files.

## Conventions & Constraints

- PHP runtime version is constrained to `^8.2` in `composer.json`.
- Composer packages are resolved from Packagist plus one local `path` repository; no private Packagist/Composer repositories are declared.
- NPM packages are resolved from the default public registry; `.npmrc` contains no `registry=` entry.
- All npm postinstall scripts are ignored globally via `ignore-scripts=true` in `.npmrc`.
- The local library `packages/ContentBlocks` follows Laravel package conventions (its own `composer.json`, PSR-4 namespace `Highblossom\ContentBlocks\`, service provider registered under `extra.laravel.providers`).
- Composer's `allow-plugins` acts as an explicit whitelist — only the two listed plugins are permitted to run arbitrary code during install.