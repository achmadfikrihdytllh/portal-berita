# Portal Berita — Laravel (Struktur Awal)

Paket ini berisi **migration**, **model**, dan **seeder** untuk pondasi portal berita.
Karena sandbox saya tidak punya akses ke Packagist (repo Composer), file di sini
perlu ditempel ke project Laravel asli yang kamu buat di komputer/servermu sendiri.

## 1. Cara Pasang

```bash
# 1. Buat project Laravel baru
composer create-project laravel/laravel news-portal
cd news-portal

# 2. Copy isi folder ini ke dalam project
#    - database/migrations/*.php  -> ke database/migrations/
#    - database/seeders/*.php     -> ke database/seeders/
#    - app/Models/*.php           -> ke app/Models/ (timpa User.php bawaan)

# 3. Set koneksi database di .env, lalu migrate + seed
php artisan migrate --seed
```

Akun admin default setelah seeding:
- Email: `admin@portalberita.test`
- Password: `password`
(**Wajib diganti** setelah login pertama.)

## 2. Skema Database

| Tabel | Fungsi |
|---|---|
| `settings` | Key-value store untuk pengaturan situs: nama, tagline, **logo**, favicon, dan **semua warna tema** (header, footer, primary, secondary). Ini yang dipakai fitur custom warna & logo. |
| `categories` | Kategori berita, mendukung sub-kategori lewat `parent_id`. |
| `tags` | Tag berita. |
| `news` | Artikel berita: judul, slug, konten, thumbnail, status (draft/published/archived), headline, breaking news, views, SEO meta. |
| `news_tag` | Pivot many-to-many antara `news` dan `tags`. |
| `comments` | Komentar berita, mendukung reply berjenjang (`parent_id`) dan moderasi (`status`). |
| `newsletter_subscribers` | Pelanggan newsletter. |
| `users` (+kolom baru) | Ditambah `role` (admin/editor/author/reader) dan `avatar`. |

## 3. Kenapa Settings Pakai Key-Value, Bukan Kolom Tetap?

Supaya admin bisa menambah pengaturan tampilan baru di masa depan (misal warna
tombol, warna link, dsb) **tanpa migration baru**. Cara pakai di kode:

```php
use App\Models\Setting;

// Ambil satu nilai
$primary = Setting::get('color_primary', '#2563eb');

// Simpan/update
Setting::set('color_primary', '#ff5722', type: 'color', group: 'appearance');

// Ambil semua setting appearance sekaligus (untuk header/footer Blade)
$appearance = Setting::group('appearance');
```

Di layout utama nanti, warna-warna ini dirender jadi CSS variables:

```blade
<style>
  :root {
    --color-primary: {{ Setting::get('color_primary', '#2563eb') }};
    --color-header-bg: {{ Setting::get('color_header_bg', '#ffffff') }};
    --color-footer-bg: {{ Setting::get('color_footer_bg', '#111827') }};
  }
</style>
```

lalu di Tailwind config, warna-warna itu dipetakan supaya class seperti
`bg-primary` otomatis ikut berubah sesuai setting admin — tanpa perlu rebuild
CSS setiap admin ganti warna.

## 4. Struktur Folder yang Direkomendasikan (Standar MVC Rapi)

```
app/
  Http/
    Controllers/
      Admin/            <- controller khusus dashboard admin
        DashboardController.php
        NewsController.php
        CategoryController.php
        SettingController.php
        CommentController.php
      Front/             <- controller untuk halaman publik
        HomeController.php
        NewsController.php
        CategoryController.php
        SearchController.php
    Requests/            <- Form Request untuk validasi (per fitur)
      Admin/StoreNewsRequest.php
      Admin/UpdateNewsRequest.php
  Models/                <- (sudah dibuat di paket ini)
  Services/              <- logic bisnis dipisah dari controller
    NewsService.php
    SettingService.php
  Policies/              <- otorisasi per role (admin/editor/author)
    NewsPolicy.php

resources/
  views/
    admin/
      layouts/
      news/
      categories/
      settings/
    front/
      layouts/
      home.blade.php
      news/
      categories/
    components/          <- Blade component (card berita, navbar, dll)

routes/
  web.php                <- publik
  admin.php              <- di-include khusus grup /admin dengan middleware role
```

Prinsip MVC yang dipakai:
- **Model** murni untuk relasi & query data (sudah di paket ini).
- **Controller** tetap tipis — cukup terima request, panggil Service, kembalikan
  view/response. Logic rumit (misal generate slug unik, resize gambar, hitung
  trending) ditaruh di **Service**, bukan di Controller.
- **Form Request** untuk validasi, bukan divalidasi manual di controller.
- **Policy** untuk aturan "siapa boleh apa" (author hanya bisa edit berita
  miliknya sendiri, editor bisa semua, dst).

## 5. Controller & Routes Admin (Update)

File baru yang ditambahkan:

```
app/Http/Middleware/EnsureUserHasRole.php
app/Services/NewsService.php
app/Services/SettingService.php
app/Http/Requests/Admin/StoreNewsRequest.php
app/Http/Requests/Admin/UpdateNewsRequest.php
app/Http/Requests/Admin/StoreCategoryRequest.php
app/Http/Requests/Admin/UpdateAppearanceRequest.php
app/Http/Controllers/Admin/DashboardController.php
app/Http/Controllers/Admin/NewsController.php
app/Http/Controllers/Admin/CategoryController.php
app/Http/Controllers/Admin/SettingController.php   <- fitur custom warna & logo
app/Http/Controllers/Admin/CommentController.php
app/Policies/NewsPolicy.php
routes/admin.php
```

### 5.1 Daftarkan middleware role

**Laravel 11 (bootstrap/app.php):**
```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => \App\Http\Middleware\EnsureUserHasRole::class,
    ]);
})
```

**Laravel 10 ke bawah (app/Http/Kernel.php):**
```php
protected $middlewareAliases = [
    // ...
    'role' => \App\Http\Middleware\EnsureUserHasRole::class,
];
```

### 5.2 Daftarkan Policy

Di `app/Providers/AppServiceProvider.php` (atau `AuthServiceProvider.php` kalau masih ada):
```php
use App\Models\News;
use App\Policies\NewsPolicy;
use Illuminate\Support\Facades\Gate;

public function boot(): void
{
    Gate::policy(News::class, NewsPolicy::class);
}
```

### 5.3 Include routes/admin.php ke routes/web.php

```php
Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));
```

### 5.4 Storage link (wajib untuk upload logo/thumbnail)

```bash
php artisan storage:link
```

Tanpa ini, file yang di-upload ke `storage/app/public/...` tidak akan bisa
diakses lewat URL publik (`asset('storage/...')`).

### 5.5 Alur Fitur Custom Warna & Logo

1. Admin buka `/admin/settings/appearance`.
2. Form menampilkan color picker untuk tiap warna (`color_primary`,
   `color_header_bg`, dst) plus input file untuk logo & favicon — nilai
   awalnya diambil dari `Setting::group('appearance')`.
3. Submit ke `PUT /admin/settings/appearance` → divalidasi oleh
   `UpdateAppearanceRequest` (format hex wajib `#RRGGBB`) → diproses
   `SettingService::saveAppearance()` yang meng-update tabel `settings` dan
   otomatis menghapus logo/favicon lama saat diganti.
4. Layout publik (`resources/views/front/layouts/app.blade.php`) membaca
   `Setting::group('appearance')` lalu inject sebagai CSS variable di `<style>`,
   sehingga header, footer, dan tombol otomatis ikut warna baru tanpa
   rebuild asset.

## 6. Controller & Routes Front (Publik)

File baru:

```
app/Http/Controllers/Front/HomeController.php       <- headline, breaking, trending, per-kategori
app/Http/Controllers/Front/NewsController.php        <- listing + detail + komentar
app/Http/Controllers/Front/CategoryController.php     <- halaman per kategori
app/Http/Controllers/Front/SearchController.php       <- pencarian
app/Http/Controllers/Front/NewsletterController.php   <- subscribe newsletter
app/Http/Requests/Front/StoreCommentRequest.php
routes/front.php
```

### 6.1 Include ke routes/web.php

```php
require base_path('routes/front.php');

Route::prefix('admin')->name('admin.')
    ->middleware(['auth', 'role:admin,editor,author'])
    ->group(base_path('routes/admin.php'));
```

### 6.2 Catatan Penting

- **Route model binding pakai slug**: `News` & `Category` sudah override
  `getRouteKeyName()` jadi `slug`, sehingga URL otomatis rapi
  (`/berita/judul-berita-xxxx`, `/kategori/teknologi`) tanpa kode tambahan
  di controller.
- **Komentar guest vs login**: komentar dari user yang login otomatis
  `approved`, komentar guest masuk status `pending` dan menunggu moderasi
  admin di `/admin/comments`.
- **Rate limiting**: form komentar & newsletter subscribe sudah dibatasi
  (`throttle:10,1` dan `throttle:5,1`) supaya tidak gampang di-spam bot.
- **View belum dibuat**: controller ini return `view('front.home')`,
  `view('front.news.show')`, dst — langkah selanjutnya (bagian 7) adalah
  bikin file Blade-nya.

## 7. View Blade + Tailwind

File baru:

```
resources/views/front/layouts/app.blade.php     <- masthead, ticker breaking news, footer (warna dinamis)
resources/views/components/news-card.blade.php
resources/views/front/home.blade.php
resources/views/front/news/{index,show}.blade.php
resources/views/front/categories/show.blade.php
resources/views/front/search.blade.php

resources/views/admin/layouts/app.blade.php
resources/views/admin/dashboard.blade.php
resources/views/admin/settings/appearance.blade.php  <- FITUR UTAMA: color picker + live preview + upload logo
resources/views/admin/news/{index,create,edit,_form}.blade.php
resources/views/admin/categories/{index,create,edit,_form}.blade.php
resources/views/admin/comments/index.blade.php

app/View/Composers/FrontLayoutComposer.php    <- share breaking news ticker ke semua halaman publik

tailwind.config.js
postcss.config.js
resources/css/app.css
resources/js/app.js
```

### 8.1 Install Tailwind (di project Laravel asli kamu)

```bash
npm install -D tailwindcss postcss autoprefixer
```

File `tailwind.config.js` dan `postcss.config.js` di paket ini sudah lengkap
dan siap pakai — tinggal copy ke root project. `vite.config.js` bawaan
Laravel biasanya sudah otomatis membaca `resources/css/app.css` dan
`resources/js/app.js`, jadi umumnya tidak perlu diubah.

```bash
npm install
npm run dev   # atau: npm run build untuk production
```

### 8.2 Daftarkan View Composer

Di `app/Providers/AppServiceProvider.php`:
```php
use Illuminate\Support\Facades\View;
use App\View\Composers\FrontLayoutComposer;

public function boot(): void
{
    View::composer('front.layouts.app', FrontLayoutComposer::class);
}
```

### 8.3 Auth Admin (login)

Layout admin memakai `route('logout')` yang berasal dari scaffolding auth.
Cara tercepat: pasang **Laravel Breeze** (Blade stack):
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install && npm run build
php artisan migrate
```
Setelah itu route `login`, `logout`, dan halaman auth otomatis tersedia.
Redirect setelah login bisa diarahkan ke `/admin` lewat konfigurasi
`RouteServiceProvider::HOME` atau logic di controller login.

### 8.4 Bagaimana Warna Kustom Benar-Benar Bekerja

1. `front/layouts/app.blade.php` mengambil `Setting::group('appearance')` lalu
   menulisnya sebagai CSS variable (`--color-primary`, dst) di `<style>` pada `<head>`.
2. `tailwind.config.js` memetakan `brand.primary` → `var(--color-primary)`, dst.
   Jadi di semua Blade cukup pakai class biasa: `bg-brand-primary`, `text-brand-headerText`.
3. Saat admin ganti warna di `/admin/settings/appearance`, tidak perlu rebuild
   asset apa pun — CSS variable-nya langsung berubah karena datanya diambil
   dari database setiap request.
4. Halaman appearance sendiri punya **live preview** terpisah (JS murni,
   lihat `<script>` di bagian bawah file) yang mensimulasikan tampilan
   header/footer/tombol secara real-time sebelum admin klik simpan.

## 8. Ringkasan Lengkap — Urutan Setup dari Nol

```bash
composer create-project laravel/laravel news-portal
cd news-portal

# 1. Copy semua file dari paket ini ke project (migrations, models, services,
#    controllers, requests, policies, routes, views, config Tailwind)

# 2. Install dependency tambahan
composer require laravel/breeze --dev
php artisan breeze:install blade
npm install -D tailwindcss postcss autoprefixer
npm install

# 3. Setup .env (DB_*), lalu:
php artisan migrate --seed
php artisan storage:link

# 4. Daftarkan middleware role, Policy, dan View Composer
#    (lihat bagian 5.1, 5.2, 7.2 di atas)

# 5. Include routes di routes/web.php:
#    require base_path('routes/front.php');
#    Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin,editor,author'])
#        ->group(base_path('routes/admin.php'));

# 6. Jalankan
npm run dev
php artisan serve
```

Login admin: `admin@portalberita.test` / `password` — **ganti setelah login pertama**.
#   p o r t a l - b e r i t a  
 