# Sultaf Restaurant: Technical Documentation

How the Sultaf Restaurant app is put together. For setup instructions see [README.md](README.md); for what changed during the backend cleanup see [CHANGES.md](CHANGES.md).

---

## 1. Architecture: MVC on Laravel 13

```
app/Http/
  Controllers/
    Admin/      DashboardController, MenuController, BenefitController, PromoController
    Owner/      OwnerDashboardController, LaporanController
    Kasir/      KasirDashboardController, PosController, PembayaranController, PesananController
    Dapur/      DapurDashboardController, KitchenDisplayController, MenuAvailabilityController
    Customer/   Home, Menu, Cart, Checkout, Order, Profile, Rewards, Notification
    Auth/       AuthController (login, register, logout)
    Shared/     LocaleController (ID/EN switch)
    Api/        Auth, WaiterOrder, PaymentGateway, OtaWebhook (not routed yet)
  Middleware/   EnsureUserHasRole (alias `role`), SetLocale, TenantScope, JwtRbacScope
app/Models/     User, Menu, TransaksiPenjualan, DetailTransaksi, Member, Promo, Notifikasi, ...
app/Services/   MemberPointService
routes/web.php  every route, grouped by audience
resources/views Blade views, grouped like the controllers
```

- **Models** map to database tables.
- **Views** are Blade templates; the staff dashboards use `x-layouts.admin|owner|kasir|dapur`, the public site uses `x-layouts.app`, login/register use `x-layouts.guest`.
- **Controllers** are grouped by role, so backend and frontend work can be split per area.
- **`bootstrap/app.php`** configures routing and middleware (Laravel 11+ style): it registers the `role` alias and appends `SetLocale` to the web group. There is no `Http/Kernel` or `RouteServiceProvider`.

## 2. Routes and roles

All routes live in `routes/web.php`:

| Area | Prefix / name | Middleware |
|---|---|---|
| Public + customer | `/`, `/menu`, `/cart`, `/checkout/*`, `/orders`, `/profile`, `/rewards`, `/notifications` | `guest` for login/register, `auth` for the account pages |
| Admin | `/admin/*`, `admin.*` | `auth`, `role:admin` |
| Owner | `/owner/*`, `owner.*` | `auth`, `role:owner,admin` |
| Kasir | `/kasir/*`, `kasir.*` | `auth`, `role:kasir,admin` |
| Dapur | `/dapur/*`, `dapur.*` | `auth`, `role:dapur,admin` |

`EnsureUserHasRole` redirects guests to `/login` and returns 403 when a signed-in user's `role` is not in the allowed list. After login, `Auth\AuthController` redirects each role to its dashboard.

## 3. Database

Migrations in `database/migrations` define the schema; seeders fill sample data. SQLite is the default for local use.

| Table | Purpose |
|---|---|
| `users` | Accounts. PK `id_user`; `username`, `name`, `email`, `no_hp`, `password`, `role` (`admin`, `owner`, `kasir`, `dapur`, `customer`) |
| `menus` | Menu items. PK `id_menu`; `nama_menu`, `deskripsi`, `harga`, `foto_menu`, `rating`, `diet_type`, `is_halal`, `status_ketersediaan` (`tersedia` / `habis`) |
| `transaksi_penjualans` | Orders. PK `id_transaksi`; customer, order type, table number, status columns, `total_bayar`, `diskon`, payment method and proof |
| `detail_transaksis` | Order lines: `id_transaksi`, `id_menu`, `jumlah`, `harga_satuan`, `subtotal` |
| `members` | Loyalty members: `id_user`, `total_poin`, `tanggal_daftar` |
| `promos` | Benefits/discounts: `nama_promo`, `potongan_harga` (rupiah), `minimal_poin` |
| `notifikasis` | Promo messages sent to users (`judul`, message text in `id_pesan`) |
| `sessions`, `failed_jobs`, `password_resets` | Framework tables |
| `menu_items`, `categories`, `orders`, `order_items` | Legacy tables from the first version of the project; `categories` is still used by the admin menu form |

Relationships: a `TransaksiPenjualan` belongs to a `User` and has many `DetailTransaksi`; each `DetailTransaksi` belongs to a `Menu`; a `User` has one `Member`.

Some views still use older field names (`nama_makanan`, `harga_makanan`, `total_harga`, `qty`, ...). The models expose read-only aliases for them, so the table columns above remain the single source of truth.

## 4. Order flow

**Customer checkout:** cart (session) -> `/checkout/details` (dine-in or takeaway, table number, notes) -> `/checkout/payment` (method, name, phone, optional payment proof) -> a `TransaksiPenjualan` plus `DetailTransaksi` rows are created in one DB transaction -> `/checkout/finish/{id}`. Card payments are marked verified automatically; other methods wait for the cashier. A member discount is taken from `promos` based on the user's points.

**Kasir:** verifies pending payments (`/kasir/pembayaran`), which sends the order to the kitchen and awards points; or rings up walk-in sales at the POS and prints a receipt.

**Dapur:** moves orders through `pending` -> `cooking` -> `ready` and toggles menu availability.

**Member points:** `MemberPointService::awardFromTransaksi()` gives 1 point per Rp 10,000 to the member whose phone number matches the order.

## 5. Security

- **Passwords** are hashed with `Hash::make()` on register and password change, and checked through `Auth::attempt()`, never compared in plain text. Seeded accounts use `Hash::make()` too.
- **CSRF**: every form includes `@csrf`; a missing or expired token returns a 419 page.
- **Authorization**: `auth` + `role:...` middleware on every staff route; customers can only open their own orders.
- **Uploads** (menu photos, payment proof) are validated by type and size and stored on the `public` disk (`storage/app/public`, exposed through `php artisan storage:link`).
- **Sessions** are stored in the database.

## 6. Localization

`SetLocale` reads the `locale` value from the session (`id` or `en`); `/lang/{locale}` changes it. UI strings are wrapped in `__()`; the repository only ships the English framework files in `resources/lang/en`, so strings without a translation fall back to their source text.

## 7. Tests

`php artisan test` runs the feature tests in `tests/Feature`: public pages, every role's pages, role middleware and guest redirects, member points and the `Menu` model aliases.

## 8. Known gaps

- `Api/*` controllers are not routed: they need JWT/payment/RMS/OTA classes that are not in the repository (`JwtAuthService`, `PaymentGatewayService`, `RmsIntegrationService`, `OtaChannelSyncService`, `AuditLog`, `TransaksiItem`).
- Two menu schemas coexist (`menu_items` + `categories` legacy, `menus` current).
- The admin menu form still posts a few old fields (`category_id`, `spice_level`); the controller maps what it can and ignores the rest.
- The owner, kasir and dapur layouts are simple placeholders based on the admin layout.
