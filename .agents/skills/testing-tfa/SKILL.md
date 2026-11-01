---
name: testing-tfa
description: Test the Two-Factor Authentication flow end-to-end on the Admin Profile page. Use when verifying TFA enable/confirm/disable changes.
---

# Testing TFA on Admin Profile

## Local Environment Setup

1. Install PHP 8.4 with SQLite extension:
   ```bash
   sudo apt-get install -y php8.4-sqlite3
   ```
2. Copy `.env.example` to `.env` and generate app key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. Create SQLite database and run migrations:
   ```bash
   touch database/database.sqlite
   php artisan migrate --force
   ```
   **Note:** The gallery migration `2026_04_22_064659_convert_gallery_category_to_foreign_key.php` may fail on SQLite due to MySQL-style table aliases (`UPDATE gallery_images gi SET ...`). Temporarily replace `gallery_images gi` with `gallery_images` and `gi.` with `gallery_images.` in the migration file for local testing.
4. The admin user is created by the migration seeder (`2026_05_05_091031_seed_roles_permissions_and_admin_user`), so `php artisan db:seed` may fail with a unique constraint violation — this is expected.
5. The seeded user may not have `email_verified_at` set. Fix via tinker:
   ```bash
   php artisan tinker --execute="App\Models\User::first()->update(['email_verified_at' => now()]);"
   ```
6. Install npm dependencies and build:
   ```bash
   npm install && npm run build
   ```
7. Start the server:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

## Test Credentials

- **Admin user**: `eugeneseasole@gmail.com` / `password` (Super Admin role)
- **Profile page**: `/admin/profile` → Security tab
- Admin routes require `auth`, `verified`, and `can:access admin panel` middleware

## TFA Test Flow

1. **Verify disabled state**: Security tab shows "Enable Two-Factor Authentication" button
2. **Enable TFA**: Click the button → modal appears with QR code SVG, setup key, verification code input
3. **Test invalid code**: Enter `000000` → red error message "The provided two factor authentication code was invalid."
4. **Test pending state persistence**: Reload page → Security tab shows yellow warning banner with inline QR code, setup key, and confirm form
5. **Generate valid TOTP code**:
   ```bash
   php artisan tinker --execute="
   \$user = App\Models\User::first();
   \$secret = decrypt(\$user->two_factor_secret);
   \$google2fa = app(\PragmaRX\Google2FA\Google2FA::class);
   echo \$google2fa->getCurrentOtp(\$secret);
   "
   ```
6. **Confirm TFA**: Enter the valid code → recovery codes modal shows 8 codes in `XXXXX-XXXXX` format, success toast
7. **Verify enabled state**: Green badge "Two-factor authentication is enabled.", Regenerate Recovery Codes and Disable buttons visible
8. **Disable TFA**: Click Disable → success toast, Enable button reappears
9. **Verify DB cleanup**: All `two_factor_*` columns should be NULL after disable

## Known UX Quirks

- After form submissions (confirm error, disable), the page may redirect to the Profile tab instead of staying on Security. User must click Security tab again. This is cosmetic.
- The `showTwoFactorSetup` session flash auto-switches to Security tab on initial enable, but error redirects don't preserve tab state.

## Devin Secrets Needed

No external secrets required. All testing uses the local seeded admin account.
