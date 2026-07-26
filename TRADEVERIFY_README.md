# TradeVerify — Laravel 13 + Vue 3 Starter Overlay

This package contains the custom application files for a strictly verified B2B import/export marketplace.
It is an **overlay**, not a complete Laravel installation. Create a fresh Laravel 13 Vue starter-kit project first, then copy these files into it.

## 1. Recommended stack

- Laravel 13
- PHP 8.3 or newer
- Vue 3 + TypeScript
- Inertia.js
- Tailwind CSS
- MariaDB 10.4+ or MySQL 8
- Spatie Laravel Permission v8
- Private local/S3 storage for verification documents
- Public local/S3 storage for product images

## 2. Create the base project on Windows

Open PowerShell:

```powershell
cd C:\Users\Norayr\Downloads
composer global require laravel/installer
laravel new tradeverify
```

Choose these options in the installer:

- Starter kit: Vue
- TypeScript: Yes
- Authentication: Laravel built-in authentication
- Database: MySQL
- Testing: Pest

Then:

```powershell
cd tradeverify
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

## 3. Copy this overlay

Copy every folder in this package into the root of the new `tradeverify` project. Merge folders; do not delete starter-kit files unless this README explicitly says to replace one.

The following files are examples/snippets and must be merged manually:

- `app/Models/User.php.example`
- `app/Providers/AppServiceProvider.php.example`
- `bootstrap/app.php.example`
- `routes/web.php.example`
- `database/seeders/DatabaseSeeder.php.example`

## 4. Create the database

Using phpMyAdmin or MariaDB/MySQL CLI:

```sql
CREATE DATABASE tradeverify CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

Update `.env`:

```env
APP_NAME="TradeVerify"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tradeverify
DB_USERNAME=root
DB_PASSWORD=

FILESYSTEM_DISK=local
QUEUE_CONNECTION=database
CACHE_STORE=database
SESSION_DRIVER=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="verification@tradeverify.test"
MAIL_FROM_NAME="${APP_NAME}"

ADMIN_NAME="Platform Administrator"
ADMIN_EMAIL="admin@tradeverify.test"
ADMIN_PASSWORD="ChangeThisImmediately!123"
```

For production, never use the sample administrator password and never commit `.env`.

## 5. Enable email verification

Merge `app/Models/User.php.example` into the starter-kit User model. The model implements `MustVerifyEmail`.

In `config/fortify.php`, ensure these features remain enabled:

```php
Features::registration(),
Features::resetPasswords(),
Features::emailVerification(),
Features::twoFactorAuthentication([
    'confirm' => true,
    'confirmPassword' => true,
]),
```

## 6. Merge middleware and super-admin access

- Merge `bootstrap/app.php.example` into `bootstrap/app.php`.
- Merge `app/Providers/AppServiceProvider.php.example` into your provider.

The `verified.company` middleware blocks unapproved companies from creating trade posts.
The Gate rule gives the `super-admin` role platform-wide authorization.

## 7. Merge routes

Copy the route groups from `routes/web.php.example` into the starter-kit `routes/web.php`.
Keep the starter-kit authentication/settings routes.

## 8. Run migrations and seeders

```powershell
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan storage:link
npm install
npm run build
```

Before production deployment, connect a malware scanning service for uploaded documents. MIME validation alone is not malware scanning.

Run the application:

```powershell
composer run dev
```

Open:

```text
http://127.0.0.1:8000
```

## 9. Verification workflow

1. User registers and verifies email.
2. User submits company data and uploads legal documents.
3. Company status becomes `pending`.
4. Administrator reviews the company and documents.
5. Administrator approves, rejects, or suspends the company.
6. On approval, buyer/seller roles are assigned from the company activity type.
7. Approved users can create buy or sell posts.
8. New posts enter `pending` moderation.
9. Administrator publishes or rejects each post.
10. Every important decision is saved in `audit_logs`.

## 10. Required verification documents

For the first production release, require at least:

- Business registration certificate
- Tax/VAT registration document
- Legal representative identification
- Proof of registered business address
- Import/export license when required by the company jurisdiction or product

Optional additional documents:

- Bank account ownership letter
- Beneficial ownership declaration
- Quality certificates
- Product-specific licenses
- Chamber of commerce extract

Do not make identity or company documents publicly accessible. They are stored on Laravel's private `local` disk and downloaded only through authorized controller actions.

## 11. Production security checklist

- Use HTTPS only.
- Set `APP_DEBUG=false`.
- Use a real SMTP provider.
- Require two-factor authentication for administrators.
- Use a private S3 bucket or equivalent for verification documents.
- Scan uploaded documents for malware.
- Limit MIME types and file sizes.
- Add rate limits to login, registration, document upload, post creation, and contact forms.
- Record IP address and user agent for admin decisions.
- Back up database and private files daily.
- Add terms of service, privacy policy, sanctions/export-control policy, and prohibited-products policy.
- Never claim that platform verification guarantees creditworthiness, product legality, or transaction safety.

## 12. Main pages to finish in the UI

Public:

- Home page
- Search buy/sell posts
- Post details
- Company public profile
- Registration and login

Verified user:

- Dashboard
- Company verification form
- Verification status
- My posts
- Create/edit trade post
- Inquiries/messages (phase 2)

Administrator:

- Verification queue
- Verification review screen
- User/company management, role assignment, activation and suspension
- Post moderation
- Categories
- Countries and restricted products
- Audit logs
- Reports

## 13. Recommended next development phases

Phase 1: registration, verification, admin approval, trade posts, moderation.

Phase 2: inquiries, private messaging, saved searches, favorites, notifications.

Phase 3: registry API checks, sanctions screening, subscription plans, multilingual UI, advanced search, fraud scoring.
