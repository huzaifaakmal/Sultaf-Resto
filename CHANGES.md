# Laporan Perubahan Backend

Commit: `Resolve merge conflicts and reorganize backend per role`

Tujuan: merapikan arsitektur backend agar tim backend dan frontend bisa bekerja terpisah. **Tidak ada perubahan UI** (tampilan, CSS, dan isi Blade tidak diubah, selain penanda konflik yang dihapus).

## 1. Konflik merge diselesaikan

42 file di `main` berisi penanda konflik (`<<<<<<<`, `=======`, `>>>>>>>`), sehingga aplikasi tidak bisa berjalan. Semua konflik diselesaikan dengan **mengambil sisi HEAD** (Laravel 13, Vite + Tailwind 4). Termasuk `composer.json`, `package.json`, `bootstrap/app.php`, `routes/web.php`, `config/*`, dan beberapa view.

## 2. Controller dikelompokkan per peran

| Lama | Baru |
|---|---|
| `AdminMenuController` | `Admin/MenuController` |
| `HomeController`, `MenuController`, `CartController`, `CheckoutController`, `OrderController`, `ProfileController`, `RewardsController`, `NotificationController` | `Customer/*` |
| `LocaleController` | `Shared/LocaleController` |

Folder `Admin/DashboardController`, `Owner/`, `Kasir/`, `Dapur/`, `Auth/`, dan `Api/` tetap seperti semula. Namespace sudah disesuaikan.

## 3. Route dirapikan

- Semua route ada di satu file, `routes/web.php`: publik dan pelanggan lebih dulu, lalu grup per peran (admin, owner, kasir, dapur).
- Setiap grup peran memakai `prefix`, `name`, dan middleware `auth` + `role:...`.
- Nama route disesuaikan dengan yang dipakai view yang sudah ada (contoh `kasir.pos.add`, `dapur.menu.toggle`), jadi view tidak perlu diubah.
- Route `/admin/menu` dan route `/menu` pelanggan memakai alias (`AdminMenuController`, `AdminDashboardController`) karena nama kelasnya sama.

## 4. Middleware

- `EnsureUserHasRole` sebelumnya salah isi (berisi kode route). Sekarang berisi middleware sungguhan: `role:kasir,admin`.
- Alias `role` dan middleware `SetLocale` didaftarkan di `bootstrap/app.php`.

## 5. File dihapus

**Sisa Laravel 8 (tidak dipakai di struktur Laravel 13):**
`app/Http/Kernel.php`, `app/Console/Kernel.php`, `app/Exceptions/Handler.php`, `app/Providers/{Auth,Broadcast,Event,Route}ServiceProvider.php`, `server.php`, `webpack.mix.js`, `tests/CreatesApplication.php`, dan middleware bawaan lama (`Authenticate`, `EncryptCookies`, `TrimStrings`, `TrustHosts`, `TrustProxies`, `VerifyCsrfToken`, `PreventRequestsDuringMaintenance`, `RedirectIfAuthenticated`).

**Duplikat/legacy:**
- `RestaurantController` (digantikan `Customer/HomeController`)
- `AdminAuthController` (digantikan `Auth/AuthController`; versi lama membandingkan password plaintext)
- `IsAdmin` (digantikan `role:admin`)
- `.example-database`, `.example-env` (file kosong)

**Konfigurasi tak terpakai (paket tidak ada di `composer.json`):**
`config/sanctum.php`, `config/cors.php`, `config/broadcasting.php`, migrasi `personal_access_tokens`, `routes/api.php`, `routes/channels.php`.

## 6. Dokumentasi

`DOCUMENTATION.md` diperbarui: struktur folder baru, middleware `role`, dan daftar pekerjaan tersisa.

## 7. Migrasi duplikat dihapus & test

- Dihapus migrasi lama yang bentrok dengan skema `users`/`sessions` baru (yang dipakai model `User`, PK `id_user`):
  `2014_10_12_000000_create_users_table`, `2026_05_25_233833_add_username_and_role_to_users_table`, `2026_05_25_234702_create_sessions_table`.
- `php artisan migrate` kini berjalan sampai selesai (diuji di SQLite in-memory).
- `tests/Feature/ExampleTest` memakai `RefreshDatabase`.
- Hasil lokal: `php artisan test` -> 2 test lulus (PHP 8.4, setelah `composer install` dan `npm run build`).

## 8. Model & service yang ditambahkan

Dibuat sesuai nama kolom di migrasi (tanpa mengubah skema):
- Model: `TransaksiPenjualan`, `DetailTransaksi`, `Member`, `Promo`, `Notifikasi`.
- `App\Services\MemberPointService::awardFromTransaksi()`: menambah poin (1 poin per Rp 10.000) ke member yang nomor HP-nya cocok; mengembalikan `null` bila nomor kosong atau belum terdaftar.
- Test: `tests/Feature/MemberPointServiceTest.php`. Hasil `php artisan test`: 4 test lulus.

## Belum dikerjakan / perlu diketahui

- **Test baru sebatas bawaan.** Hanya 2 test contoh; belum ada test untuk fitur per peran.
- **Kelas masih belum ada** (tidak ada di branch mana pun di repo, kemungkinan di komputer anggota tim; minta di-commit): model `Benefit`, `PromoMessage`, `AuditLog`, `TransaksiItem`; service `RmsIntegrationService`, `JwtAuthService`, `PaymentGatewayService`, `OtaChannelSyncService`. Halaman checkout pelanggan, notifikasi, pesanan pelanggan, serta seluruh `Api/*` bergantung padanya.
- **Nama kolom tidak konsisten antar bagian**: migrasi `menus` memakai `nama_menu`/`harga`, model `Menu` memakai `name`/`price`/`habis`, sebagian view memakai `nama_makanan`. Perlu disepakati satu skema menu oleh tim backend dan frontend.
- **Dua skema menu** berdampingan: `menu_items` + `categories` (lama) dan `menus` (baru).
- **Layout** `x-layouts.owner`, `x-layouts.kasir`, `x-layouts.dapur` dipakai view tetapi belum dibuat.
- **Route API** (`Api/*`) belum didaftarkan, karena paket JWT belum ada di `composer.json`.
- **Route admin** untuk benefit dan promo dipakai view tetapi controllernya belum ada.

---

> Perbaikan arsitektur ini dibantu oleh [LinkedIn Muhammad Abiyyu Rahman](https://www.linkedin.com/in/muhammadabiyyurahman/) dan web [204l.com](https://204l.com).
