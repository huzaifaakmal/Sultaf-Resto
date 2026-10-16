# Backend Change Report

Scope: the whole backend cleanup, from the merge-conflict fix to the finished JSON API (sections 1-14 below). Final state: the web app and the API run, and `php artisan test` passes 24 of 24.

Goal: clean up the backend architecture so the backend and frontend teams can work separately. **No UI changes** (markup, CSS and Blade contents are untouched, apart from removed merge-conflict markers).

## 1. Merge conflicts resolved

42 files on `main` contained merge-conflict markers (`<<<<<<<`, `=======`, `>>>>>>>`), so the app could not run. All conflicts were resolved by **keeping the HEAD side** (Laravel 13, Vite + Tailwind 4). This includes `composer.json`, `package.json`, `bootstrap/app.php`, `routes/web.php`, `config/*` and a few views.

## 2. Controllers grouped by role

| Before | After |
|---|---|
| `AdminMenuController` | `Admin/MenuController` |
| `HomeController`, `MenuController`, `CartController`, `CheckoutController`, `OrderController`, `ProfileController`, `RewardsController`, `NotificationController` | `Customer/*` |
| `LocaleController` | `Shared/LocaleController` |

`Admin/DashboardController`, `Owner/`, `Kasir/`, `Dapur/`, `Auth/` and `Api/` stay where they were. Namespaces were updated.

## 3. Routes cleaned up

- All routes live in one file, `routes/web.php`: public and customer routes first, then one group per role (admin, owner, kasir, dapur).
- Each role group uses a `prefix`, a `name` prefix and the `auth` + `role:...` middleware.
- Route names match what the existing views call (e.g. `kasir.pos.add`, `dapur.menu.toggle`), so no view had to change.
- The admin and customer menu/dashboard controllers share class names, so they are imported with aliases (`AdminMenuController`, `AdminDashboardController`).

## 4. Middleware

- `EnsureUserHasRole` used to contain route code by mistake. It is now a real middleware: `role:kasir,admin`.
- The `role` alias and the `SetLocale` middleware are registered in `bootstrap/app.php`.

## 5. Removed files

**Laravel 8 leftovers (unused with the Laravel 13 bootstrap):**
`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`, `app/Providers/{Auth,Broadcast,Event,Route}ServiceProvider.php`, `server.php`, `webpack.mix.js`, `tests/CreatesApplication.php`, and the old stock middleware (`Authenticate`, `EncryptCookies`, `TrimStrings`, `TrustHosts`, `TrustProxies`, `VerifyCsrfToken`, `PreventRequestsDuringMaintenance`, `RedirectIfAuthenticated`).

**Duplicates / legacy:**
- `RestaurantController` (replaced by `Customer/HomeController`)
- `AdminAuthController` (replaced by `Auth/AuthController`; the old one compared plaintext passwords)
- `IsAdmin` (replaced by `role:admin`)
- `.example-database`, `.example-env` (empty files)

**Unused configuration (packages are not in `composer.json`):**
`config/sanctum.php`, `config/cors.php`, `config/broadcasting.php`, the `personal_access_tokens` migration, `routes/api.php`, `routes/channels.php`.

## 6. Documentation

`DOCUMENTATION.md` was updated: new folder layout, the `role` middleware and a list of remaining work.

## 7. Duplicate migrations removed

- Removed the old migrations that clashed with the current `users`/`sessions` schema (the one the `User` model uses, PK `id_user`):
  `2014_10_12_000000_create_users_table`, `2026_05_25_233833_add_username_and_role_to_users_table`, `2026_05_25_234702_create_sessions_table`.
- `php artisan migrate` now runs to completion (verified on in-memory SQLite).
- `tests/Feature/ExampleTest` uses `RefreshDatabase`.

## 8. Models and service added

Created to match the column names in the existing migrations (no schema change):
- Models: `TransaksiPenjualan`, `DetailTransaksi`, `Member`, `Promo`, `Notifikasi`.
- `App\Services\MemberPointService::awardFromTransaksi()`: adds points (1 point per Rp 10,000) to the member whose phone number matches; returns `null` when the number is empty or not a registered member.
- Test: `tests/Feature/MemberPointServiceTest.php`.

## 9. Menu schema made consistent

The menu fields were named three different ways (`nama_menu`/`harga` in the migration, `name`/`price` in the model, `nama_makanan`/`harga_makanan` in some views). The `menus` migration is now the single source of truth:

- `Menu` model rewritten to match the table (PK `id_menu`, fillable columns from the migration).
- New migration `2026_10_17_000001_add_details_to_menus_table` adds the columns the pages already expect: `deskripsi`, `rating`, `diet_type`, `is_halal`.
- Read-only aliases on `Menu` (`id`, `nama_makanan`, `harga_makanan`, `foto_makanan`, `habis`, `harga_format`) keep the views that use the older names working without edits.
- `Customer/CartController`, `Customer/CheckoutController` and `Customer/HomeController` now query by `id_menu` / `status_ketersediaan` instead of `id` / `habis`.
- Test: `tests/Feature/MenuModelTest.php`.

Local result: `php artisan test` passes (5 tests; PHP 8.4, after `composer install` and `npm run build`).

## 10. Web app runs end to end

Every page was exercised with real logins (admin, owner, kasir, dapur, customer): public pages, cart, checkout (details -> payment -> finish), orders, rewards, profile, POS checkout and receipt, payment verification, kitchen status, menu availability, admin menu/benefit/promo CRUD. All return 200 or the expected redirect.

Backend changes made for this:
- **Admin:** `Admin/MenuController` rewritten for the `menus` table (full CRUD, accepts both form field-name variants); new `Admin/BenefitController` (stored in `promos`) and `Admin/PromoController` (promo messages stored in `notifikasis`); dashboard shows real stats; routes added with `Route::resource`.
- **Customer checkout:** `CheckoutController` now writes `transaksi_penjualans` + `detail_transaksis` (same as the POS), member discount comes from `promos`. The calls to the missing `AuditLog`, `TransaksiItem`, `Benefit` and `RmsIntegrationService` were removed. `OrderController` and `NotificationController` use the real tables.
- **Models:** `User::member()`, `isMember()` and `points`; read-only aliases on `TransaksiPenjualan`, `DetailTransaksi`, `Promo` and `Notifikasi` so existing views work unchanged.
- **Layouts:** the missing `x-layouts.owner`, `x-layouts.kasir` and `x-layouts.dapur` components were added, modelled on the admin layout (sidebar + header + flash messages). This is the only Blade addition; no existing view was edited.
- **Seeders:** `DatabaseSeeder` now also seeds categories and 8 sample menus (photos copied to the public disk).
- **Tests:** `tests/Feature/RolePagesTest.php` (public pages, every role's pages, role middleware, guest redirect). `php artisan test`: 9 tests pass.

### Run it locally

```bash
composer install && npm install && npm run build
cp .env.example .env && php artisan key:generate
touch database/database.sqlite
# .env.example already sets CACHE_STORE=file and QUEUE_CONNECTION=sync (no cache/jobs tables exist)
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Seeded logins (password `password123`): `admin@sultaf.com`, `owner@sultaf.com`, `kasir@sultaf.com`, `dapur@sultaf.com`. Customers register on `/register`.

## 11. Small fixes

- `.env.example`: `CACHE_STORE=file`, `QUEUE_CONNECTION=sync` and `APP_NAME` so a fresh clone works without manual edits.
- Removed `AdminUserSeeder` (never called, duplicated the admin in `DatabaseSeeder`, and stored a plain-text password).

## 12. Admin menu form verified

The admin menu forms were submitted for real (browser-style requests and `tests/Feature/AdminMenuFormTest.php`): create with photo, validation errors, edit, delete. The create form's extra fields (`category_id`, `harga_modal`, `spice_level`) are accepted and ignored because `menus` has no such columns. `php artisan test`: 12 tests pass.

## 13. JSON API completed and tested

The `Api/*` controllers depended on classes that never made it into the repository. They now work against the real schema:

- Installed `php-open-source-saver/jwt-auth` (needs the `sodium` PHP extension); `User` implements `JWTSubject`; `config/jwt.php` published; `JWT_SECRET` generated by `php artisan jwt:secret`.
- Added `App\Services\JwtAuthService` (role -> scopes, create/refresh/invalidate tokens) and `App\Services\PaymentGatewayService` (Stripe when configured, mock intent otherwise, HMAC webhook verification).
- `routes/api.php` registered in `bootstrap/app.php`; `jwt.scope` middleware alias added.
- `AuthController`: uses the real user fields (`id_user`, `no_hp`, `points`).
- `WaiterOrderController`: rewritten for `transaksi_penjualans` + `detail_transaksis` (table numbers from `no_meja`, 10% tax, `WTR-` order codes).
- `PaymentGatewayController`: the webhook now **requires a valid HMAC signature** (`X-Signature`, secret `PAYMENT_WEBHOOK_SECRET`); the original accepted unsigned requests and marked orders paid. Only success events change the order, and member points are awarded once (replays are ignored).
- Removed code that has no counterpart in a restaurant app or in the database: `OtaWebhookController` (hotel booking channels and a `ProcessInboundBookingJob` that never existed), `TenantScopeMiddleware` and all `tenant_id`/`AuditLog`/`TransaksiItem` usage (no such tables), and the RMS integration calls.
- `.env.example` and `phpunit.xml` got the JWT/webhook settings.

Verification: `tests/Feature/ApiTest.php` (12 tests: login ok/failed/invalid, `me`, refresh blacklists the old token, logout, scope enforcement incl. `admin:all`, missing/garbage tokens, waiter order create/validation/show, table status, payment intent, unsigned/wrongly signed/signed webhooks, replay, Stripe-style payload) plus a manual run of every endpoint with curl against `php artisan serve`. `php artisan test`: all tests pass.

## 14. Tests run on a clean clone

The suite initially passed only on the author's machine. A fresh `git clone` + `composer install` (no `.env`, no `npm run build`) exposed two gaps, now fixed:

- `phpunit.xml` has its own `APP_KEY` (plus `JWT_SECRET` and `PAYMENT_WEBHOOK_SECRET`), so web tests no longer fail with "No application encryption key".
- `tests/TestCase.php` calls `withoutVite()`, so tests do not need the compiled frontend assets.

Final check on a fresh clone and in the working folder: 24 tests, 147 assertions, all passing.

## Still open

- **Test coverage:** feature tests cover public pages, role access, admin menu forms, member points, the model aliases and the whole API. Customer checkout, kasir payment verification and the kitchen flow were exercised manually only.
- **Stripe SDK** is not installed: payment intents are mock unless `stripe/stripe-php` is added and `STRIPE_SECRET` is set.
- **Legacy tables** `menu_items`, `orders`, `order_items` (and their models) are unused; `categories` is only read by the admin menu form. They can be dropped once the team agrees.
- **Layouts** `owner`, `kasir` and `dapur` are simple placeholders based on the admin layout; the frontend owner may want to restyle them.
- **Admin menu form** (`_form.blade.php`) still posts old field names (e.g. `category_id`, `spice_level`); the controller maps what it can and ignores the rest.

---

> This architecture cleanup was assisted by [Muhammad Abiyyu Rahman's LinkedIn](https://www.linkedin.com/in/muhammadabiyyurahman/) and the web [204l.com](https://204l.com).
