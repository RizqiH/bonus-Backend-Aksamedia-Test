<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# TES BACKEND (SQL) -> BONUS NILAI

Aplikasi Laravel untuk menampilkan data nilai RT (Requirement Test) dan ST (Scholastic Test) dengan perhitungan menggunakan SQL.

## Requirement

- PHP >= 8.2
- Composer
- Laravel 12.x
- MySQL
- phpMyAdmin (opsional untuk manajemen database)

## Setup Aplikasi

### 1. Install Dependencies
```bash
composer install
```

### 2. Konfigurasi Database
Buat file `.env` di root project dan tambahkan konfigurasi berikut:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:YourGeneratedKey
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=db.fr-pari1.bengt.wasmernet.com
DB_PORT=10272
DB_DATABASE=nilai
DB_USERNAME=b1c4a2077de08000c1fdf7c1c254
DB_PASSWORD=0687b1c4-a207-7eed-8000-6f02857a3499
```

### 3. Generate Application Key
```bash
php artisan key:generate
```

### 4. Test Koneksi Database
```bash
php artisan tinker
```
Kemudian di dalam tinker:
```php
DB::connection()->getPdo();
```

### 5. Jalankan Server
```bash
php artisan serve
```

Server akan berjalan di: `http://localhost:8000`

## Endpoint API

### 1. Endpoint `/nilaiRT`
**URL:** `GET /nilaiRT`

**Deskripsi:** Menampilkan data nilai RT (Requirement Test) untuk semua siswa.

**Kriteria:**
- Menggunakan `materi_uji_id = 7`
- Tidak termasuk pelajaran khusus
- Menampilkan nilai: REALISTIC, INVESTIGATIVE, ARTISTIC, SOCIAL, ENTERPRISING, CONVENTIONAL

**Contoh Response:**
```json
[
  {
    "nama": "Ahmad Fadlan",
    "nisn": "3097012709",
    "nilaiRT": {
      "realistic": 4,
      "investigative": 2,
      "artistic": 2,
      "social": 2,
      "enterprising": 4,
      "conventional": 2
    }
  }
]
```

### 2. Endpoint `/nilaiST`
**URL:** `GET /nilaiST`

**Deskripsi:** Menampilkan data nilai ST (Scholastic Test) untuk semua siswa dengan perhitungan khusus.

**Kriteria:**
- Menggunakan `materi_uji_id = 4`
- Perhitungan khusus:
  - `pelajaran_id 44` (Verbal) dikali `41.67`
  - `pelajaran_id 45` (Kuantitatif) dikali `29.67`
  - `pelajaran_id 46` (Penalaran) dikali `100`
  - `pelajaran_id 47` (Figural) dikali `23.81`
- Hasil diurutkan berdasarkan total nilai terbesar

**Contoh Response:**
```json
[
  {
    "nama": "Muhammad Sanusi",
    "nisn": "0094494403",
    "listNilai": {
      "verbal": 208.35,
      "kuantitatif": 89.01,
      "penalaran": 200,
      "figural": 142.86
    },
    "total": 640.22
  }
]
```

## Implementasi SQL

### Query untuk Nilai RT
```sql
SELECT 
    nama,
    nisn,
    MAX(CASE WHEN nama_pelajaran = 'REALISTIC' THEN skor ELSE 0 END) as realistic,
    MAX(CASE WHEN nama_pelajaran = 'INVESTIGATIVE' THEN skor ELSE 0 END) as investigative,
    MAX(CASE WHEN nama_pelajaran = 'ARTISTIC' THEN skor ELSE 0 END) as artistic,
    MAX(CASE WHEN nama_pelajaran = 'SOCIAL' THEN skor ELSE 0 END) as social,
    MAX(CASE WHEN nama_pelajaran = 'ENTERPRISING' THEN skor ELSE 0 END) as enterprising,
    MAX(CASE WHEN nama_pelajaran = 'CONVENTIONAL' THEN skor ELSE 0 END) as conventional
FROM nilai 
WHERE materi_uji_id = 7 
AND nama_pelajaran NOT IN ('Pelajaran Khusus')
AND nama_pelajaran IN ('REALISTIC', 'INVESTIGATIVE', 'ARTISTIC', 'SOCIAL', 'ENTERPRISING', 'CONVENTIONAL')
GROUP BY nama, nisn
ORDER BY nama ASC
```

### Query untuk Nilai ST
```sql
SELECT 
    nama,
    nisn,
    MAX(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) as verbal,
    MAX(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) as kuantitatif,
    MAX(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) as penalaran,
    MAX(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END) as figural,
    (
        MAX(CASE WHEN pelajaran_id = 44 THEN skor * 41.67 ELSE 0 END) +
        MAX(CASE WHEN pelajaran_id = 45 THEN skor * 29.67 ELSE 0 END) +
        MAX(CASE WHEN pelajaran_id = 46 THEN skor * 100 ELSE 0 END) +
        MAX(CASE WHEN pelajaran_id = 47 THEN skor * 23.81 ELSE 0 END)
    ) as total_nilai
FROM nilai 
WHERE materi_uji_id = 4 
AND pelajaran_id IN (44, 45, 46, 47)
GROUP BY nama, nisn
ORDER BY total_nilai DESC
```

## Testing Endpoint

Setelah server berjalan, Anda dapat mengakses endpoint berikut:

1. **Nilai RT:** `http://localhost:8000/nilaiRT`
2. **Nilai ST:** `http://localhost:8000/nilaiST`

Gunakan browser, Postman, atau curl untuk testing:
```bash
curl http://localhost:8000/nilaiRT
curl http://localhost:8000/nilaiST
```

## Fitur Teknis

- **Perhitungan SQL:** Semua perhitungan dilakukan di level database menggunakan SQL
- **Query Optimization:** Menggunakan CASE WHEN dan MAX untuk pivot data
- **Response Format:** JSON dengan struktur sesuai requirement
- **Penamaan Variabel:** Menggunakan nama yang deskriptif dan mudah dipahami
- **Sorting:** Data nilaiRT diurutkan berdasarkan nama, nilaiST berdasarkan total nilai

## Troubleshooting

### Error Koneksi Database
- Pastikan kredensial database benar
- Cek apakah server database dapat diakses
- Verifikasi port dan host

### Endpoint Tidak Ditemukan
- Pastikan server Laravel berjalan
- Cek route dengan: `php artisan route:list`

### Data Kosong
- Verifikasi data di database menggunakan phpMyAdmin
- Cek apakah materi_uji_id dan pelajaran_id sesuai
