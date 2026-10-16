# Sultaf Restaurant

A web ordering and restaurant-management app for **Sultaf Restaurant** (Pakistani & Yemeni cuisine, Yogyakarta). Customers browse the menu and order online; staff manage payments, the kitchen and reporting from role-based dashboards.

Built with **Laravel 13**, Blade, Vite and Tailwind CSS 4. This is a group course project.

## Features

| Role | What they can do |
|---|---|
| **Customer** | Browse the menu, cart, checkout (dine-in / takeaway, several payment methods, payment proof upload), order history, rewards and points, promo notifications, profile, ID/EN language switch |
| **Admin** | Dashboard stats, menu CRUD (with photo upload), benefits/promotions, promo messages to customers |
| **Owner** | Sales dashboard and sales/financial report |
| **Kasir** (cashier) | Point of sale with receipt, payment verification, order status board, member points by phone number |
| **Dapur** (kitchen) | Kitchen display (order status), menu availability toggle |

## Requirements

- PHP 8.4+ with the `mbstring`, `openssl`, `pdo_sqlite`, `fileinfo`, `curl` and `sodium` extensions
- Composer 2
- Node.js 20.19+ (or 22.12+) and npm

## Quick start

```bash
composer install
npm install && npm run build

cp .env.example .env
php artisan key:generate
php artisan jwt:secret              # signing key for the API tokens

touch database/database.sqlite        # SQLite is the default database
php artisan migrate --seed
php artisan storage:link              # serves uploaded and seeded menu photos

php artisan serve                     # http://localhost:8000
```

`.env.example` already uses the `file` cache and `sync` queue (the project has no cache/jobs tables). For frontend development run `npm run dev` instead of `npm run build`.

### Seeded accounts

All seeded accounts use the password `password123`.

| Role | Email |
|---|---|
| Admin | `admin@sultaf.com` |
| Owner | `owner@sultaf.com` |
| Kasir | `kasir@sultaf.com` |
| Dapur | `dapur@sultaf.com` |

Customers sign up at `/register`. After login, each role is redirected to its own dashboard (`/admin/dashboard`, `/owner/dashboard`, `/kasir/dashboard`, `/dapur/dashboard`).

## API

JSON API under `/api`, authenticated with JWT bearer tokens (`php-open-source-saver/jwt-auth`). Log in with the same staff accounts; each role gets permission scopes (see `app/Services/JwtAuthService.php`).

| Method | Endpoint | Auth |
|---|---|---|
| POST | `/api/auth/login` | public (`email`, `password`), throttled |
| POST | `/api/auth/refresh` | bearer token (old token is blacklisted) |
| POST | `/api/auth/logout`, GET `/api/auth/me` | bearer token |
| GET | `/api/waiter/tables`, `/api/waiter/orders/{id or code}` | scope `orders:read` |
| POST | `/api/waiter/orders` | scope `orders:write` |
| POST | `/api/payments/intent` | scope `payments:write` |
| POST | `/api/payments/webhook` | HMAC-SHA256 of the body in `X-Signature`, using `PAYMENT_WEBHOOK_SECRET` |

`admin` has the `admin:all` scope and passes every check. Payments return a mock intent unless `STRIPE_SECRET` is set and the Stripe SDK is installed.

```bash
curl -X POST http://localhost:8000/api/auth/login -H "Accept: application/json"      -d "email=kasir@sultaf.com&password=password123"
```

## Project structure

```
app/Http/Controllers/
  Admin/  Owner/  Kasir/  Dapur/   one folder per staff role
  Customer/                        public site: home, menu, cart, checkout, orders, profile, rewards
  Auth/  Shared/  Api/             login/register, language switch, JSON API (routes/api.php)
app/Http/Middleware/EnsureUserHasRole.php   `role:kasir,admin` route middleware
app/Models/  app/Services/         Eloquent models, MemberPointService
routes/web.php                     all routes: public/customer first, then one group per role
resources/views/                   Blade views, grouped like the controllers
database/migrations|seeders/       schema and sample data (menus, categories, staff accounts)
tests/Feature/                     feature tests
```

## Tests

```bash
php artisan test
```

## Documentation

- [DOCUMENTATION.md](DOCUMENTATION.md): architecture, database, security and request flow
- [CHANGES.md](CHANGES.md): report of the backend cleanup, what was fixed and what is still open

## Known gaps

See [CHANGES.md](CHANGES.md#still-open): the admin menu form still posts a few fields the `menus` table does not have, and the owner/kasir/dapur layouts are simple placeholders.

---

*Sultaf Restaurant group project, Yogyakarta, 2026. Architecture cleanup assisted by [Muhammad Abiyyu Rahman](https://www.linkedin.com/in/muhammadabiyyurahman/) and [204l.com](https://204l.com).*
