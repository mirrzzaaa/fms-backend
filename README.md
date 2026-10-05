<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Lion FMS (File Management System) - Backend API

## 📋 Deskripsi Project

Lion FMS Backend adalah RESTful API yang dikembangkan menggunakan framework **Laravel** dan database **PostgreSQL**. Sistem ini berfungsi untuk:

- Mengelola struktur direktori perusahaan (folder utama dan sub-folder)
- Mengelola dokumen file per departemen
- Mengatur hak akses berbasis peran (*Role-Based Access Control*) antara **Admin** dan **Viewer**
- Menyediakan fitur pratinjau dan unduh dokumen

---

## ⚙️ Requirement

Pastikan perangkat Anda telah terinstal:

- **PHP** >= 8.2
- **Composer**
- **PostgreSQL**

---

## 📥 Instalasi

1. Clone repository ini ke komputer lokal Anda:

   ```bash
   git clone <url-repository-anda>
   cd fms-backend
   ```

2. Install seluruh dependencies menggunakan Composer:

   ```bash
   composer install
   ```

---

## 🔧 Konfigurasi Environment

1. Salin file contoh konfigurasi environment:

   ```bash
   cp .env.example .env
   ```

2. Generate application key:

   ```bash
   php artisan key:generate
   ```

3. Buka file `.env` dan sesuaikan konfigurasi database PostgreSQL Anda:

   ```env
   DB_CONNECTION=pgsql
   DB_HOST=127.0.0.1
   DB_PORT=5432
   DB_DATABASE=nama_database_anda
   DB_USERNAME=postgres
   DB_PASSWORD=password_anda
   ```

---

## 🗄️ Migration & Seeder

Jalankan perintah berikut untuk membuat tabel database sekaligus mengisi data awal (data dummy departemen, folder ber-parent, file, dan akun pengguna):

```bash
php artisan migrate:fresh --seed
```

> **Catatan:** Perintah di atas akan mereset database, menjalankan migrasi, lalu mengeksekusi `LionFmsSeeder`.

Hubungkan direktori storage publik agar file dapat diakses oleh frontend:

```bash
php artisan storage:link
```

---

## 🚀 Menjalankan Project

Jalankan server lokal Laravel:

```bash
php artisan serve
```

Backend API akan berjalan di **http://127.0.0.1:8000**.

---

## 🔑 Akun Login (Seeder Credentials)

Gunakan kredensial berikut untuk masuk ke aplikasi melalui frontend:

| Role              | Email                    | Password          | Hak Akses                                                                 |
| ----------------- | ------------------------ | ----------------- | ------------------------------------------------------------------------- |
| **Administrator** | `admin@liongroup.co.id`  | `LionGroup2026!`  | Kontrol penuh: upload, edit, hapus file & folder, kelola departemen       |
| **Viewer**        | `viewer@liongroup.co.id` | `LionGroup2026!`  | Hanya baca: jelajahi direktori, pratinjau dokumen, dan unduh file         |

> ⚠️ Akun hanya untuk keperluan development

---

## 📄 License

Project ini dibangun di atas framework Laravel yang berlisensi [MIT](https://opensource.org/licenses/MIT).