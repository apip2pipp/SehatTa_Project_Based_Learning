# 🚀 Panduan Lengkap Setup Avenution (Untuk Pemula)

## 📋 Daftar Isi
1. [Apa yang Harus Diinstall](#1-apa-yang-harus-diinstall)
2. [Langkah-Langkah Setup](#2-langkah-langkah-setup)
3. [Menjalankan Project](#3-menjalankan-project)
4. [Cara Login](#4-cara-login)
5. [Troubleshooting](#5-troubleshooting)

---

## 1. Apa yang Harus Diinstall?

### ✅ Software yang Diperlukan:

#### A. **Laragon** (Sudah Include PHP, MySQL, Composer)
- Download: https://laragon.org/download/
- Pilih versi "Laragon Full" (sudah include semua yang dibutuhkan)
- Install seperti biasa (Next, Next, Finish)
- **Kegunaan**: Server lokal untuk menjalankan PHP dan MySQL

#### B. **Node.js** (Untuk Build Frontend)
- Download: https://nodejs.org/ (Pilih versi LTS)
- Install seperti biasa
- **Kegunaan**: Untuk menjalankan npm (Node Package Manager)

#### C. **Git** (Opsional, kalau belum punya projectnya)
- Download: https://git-scm.com/
- **Kegunaan**: Clone project dari GitHub

#### D. **Visual Studio Code** (Opsional, tapi recommended)
- Download: https://code.visualstudio.com/
- **Kegunaan**: Text editor untuk coding

---

## 2. Langkah-Langkah Setup

### 📌 Langkah 1: Start Laragon

1. Buka aplikasi **Laragon**
2. Klik tombol **"Start All"** (pojok kiri atas)
3. Tunggu sampai Apache dan MySQL berwarna hijau
4. Laragon sekarang sudah jalan!

---

### 📌 Langkah 2: Buka Terminal/Command Prompt

**Cara 1:** Lewat Laragon
- Di Laragon, klik menu → Terminal → Terminal

**Cara 2:** Lewat PowerShell/CMD
- Tekan `Windows + R`
- Ketik `cmd` atau `powershell`, Enter
- Masuk ke folder project:
```bash
cd D:\PROJECT-GITHUB\Avenution_Project_Based_Learning\avenution
```

---

### 📌 Langkah 3: Install Dependencies Backend (PHP)

Di terminal, ketik perintah ini:

```bash
cd avenution
composer install
```

**Tunggu** sampai selesai (bisa beberapa menit). Ini akan download semua library PHP yang dibutuhkan.

---

### 📌 Langkah 4: Install Dependencies Frontend (JavaScript)

Masih di terminal, ketik:

```bash
npm install
```

**Tunggu** sampai selesai. Ini akan download library untuk tampilan website.

---

### 📌 Langkah 5: Setup File Environment

File `.env` adalah file konfigurasi rahasia (database password, dll).

**Copy file `.env.example` menjadi `.env`:**

**Cara Manual:**
1. Buka folder `avenution`
2. Cari file `.env.example`
3. Copy paste file tersebut
4. Rename copy-annya jadi `.env` (tanpa .example)

**Cara via Terminal:**
```bash
copy .env.example .env
```

---

### 📌 Langkah 6: Edit File .env

Buka file `.env` dengan text editor (VS Code / Notepad++), lalu ubah bagian database:

**Ubah dari:**
```env
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

**Menjadi:**
```env
DB_DATABASE=avenution
DB_USERNAME=root
DB_PASSWORD=
```

**Catatan:** 
- `DB_PASSWORD` dikosongkan karena Laragon default tanpa password
- Jika pakai XAMPP dan ada password MySQL, isi di `DB_PASSWORD=password_kamu`

**Save file** setelah diedit!

---

### 📌 Langkah 7: Generate Application Key

Di terminal, ketik:

```bash
php artisan key:generate
```

Ini akan mengisi `APP_KEY` di file `.env` secara otomatis.

---

### 📌 Langkah 8: Buat Database

**Cara 1: Via HeidiSQL (di Laragon)**
1. Buka Laragon
2. Klik menu → Database → heidiSQL
3. Klik kanan di panel kiri → Create new → Database
4. Nama database: `avenution`
5. Charset: `utf8mb4_unicode_ci`
6. Klik OK

**Cara 2: Via Terminal/Command**
```bash
mysql -u root -e "CREATE DATABASE IF NOT EXISTS avenution CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

### 📌 Langkah 9: Migrate Database + Seeder (Isi Data Awal)

Di terminal, jalankan perintah ini:

```bash
php artisan migrate:fresh --seed
```

**Apa yang terjadi?**
- Membuat semua tabel di database (users, foods, analyses, recommendations, dll)
- Mengisi data awal (admin account, user account, data makanan)

**Tunggu** sampai muncul tulisan hijau "Database seeding completed successfully."

---

### 📌 Langkah 10: Build Frontend Assets

Di terminal, jalankan:

```bash
npm run build
```

**Apa yang terjadi?**
- Compile file CSS Tailwind
- Compile file JavaScript
- Menghasilkan file production di folder `public/build`

**Tunggu** sampai selesai (muncul "build successful").

---

## 3. Menjalankan Project

### 🎯 Start Development Server

Di terminal, jalankan:

```bash
php artisan serve
```

**Jangan tutup terminal ini!** Biarkan tetap jalan.

Kamu akan lihat output seperti ini:
```
INFO  Server running on [http://127.0.0.1:8000].

Press Ctrl+C to stop the server
```

---

### 🌐 Buka di Browser

1. Buka browser (Chrome/Firefox/Edge)
2. Ketik di address bar:
```
http://localhost:8000
```
atau
```
http://127.0.0.1:8000
```

**Tadaaa!** 🎉 Website Avenution sekarang jalan!

---

## 4. Cara Login

### 👤 Login sebagai User Biasa

```
Email: user@avenution.com
Password: password
```

**Akses:**
- Dashboard user
- Analisis kondisi tubuh
- History analisis
- Rekomendasi makanan personal

---

### 👨‍💼 Login sebagai Admin

```
Email: admin@avenution.com
Password: password
```

**Akses:**
- Dashboard admin
- CRUD data makanan
- Monitor semua user
- Analytics lengkap

---

## 5. Troubleshooting

### ❌ Error: "Access denied for user 'root'@'localhost'"

**Solusi:**
- Pastikan MySQL sudah jalan di Laragon (lampu hijau)
- Cek file `.env`, pastikan `DB_PASSWORD` kosong (kalau Laragon)
- Jika pakai XAMPP dan ada password, isi `DB_PASSWORD=password_mysql_kamu`

---

### ❌ Error: "Base table or view not found"

**Solusi:**
- Database belum di-migrate. Jalankan:
```bash
php artisan migrate:fresh --seed
```

---

### ❌ Error: "No application encryption key has been specified"

**Solusi:**
```bash
php artisan key:generate
```

---

### ❌ Error: "npm command not found"

**Solusi:**
- Node.js belum terinstall atau belum masuk PATH
- Install Node.js dari https://nodejs.org/
- Restart terminal setelah install

---

### ❌ Error: "composer command not found"

**Solusi:**
- Pastikan Laragon sudah jalan
- Atau install Composer manual: https://getcomposer.org/

---

### ❌ Tampilan berantakan / CSS tidak muncul

**Solusi:**
```bash
npm run build
```

Jika masih berantakan, coba:
```bash
php artisan optimize:clear
npm run build
```

---

### ❌ Halaman blank / Error 500

**Solusi:**
1. Cek folder `storage` dan `bootstrap/cache` harus writable:
```bash
# Di PowerShell (as Administrator)
icacls "storage" /grant Everyone:F /t
icacls "bootstrap/cache" /grant Everyone:F /t
```

2. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🎯 Ringkasan Perintah Penting

| Perintah | Kegunaan |
|----------|----------|
| `composer install` | Install PHP dependencies |
| `npm install` | Install JavaScript dependencies |
| `npm run build` | Build frontend (production) |
| `npm run dev` | Build frontend (development - watch mode) |
| `php artisan serve` | Jalankan server lokal |
| `php artisan migrate:fresh --seed` | Reset & isi database |
| `php artisan key:generate` | Generate APP_KEY |
| `php artisan optimize:clear` | Clear semua cache |

---

## 🔄 Development Mode (Untuk Coding)

Jika kamu mau edit CSS/JS dan langsung lihat perubahan tanpa build ulang:

**Terminal 1 (Server):**
```bash
php artisan serve
```

**Terminal 2 (Frontend Watch):**
```bash
npm run dev
```

Biarkan kedua terminal tetap jalan sambil coding!

---

## 📱 Alur Penggunaan Aplikasi

### Untuk Guest (tanpa login):
1. Buka homepage
2. Klik "Try Free Analysis"
3. Isi form dengan data kesehatan kamu
4. Lihat hasil rekomendasi makanan

### Untuk User (setelah login):
1. Login dengan akun user
2. Dashboard → "New Analysis"
3. Isi form analisis
4. Lihat hasil + history tersimpan

### Untuk Admin:
1. Login dengan akun admin
2. Dashboard → Lihat statistik
3. Manage Foods → CRUD data makanan
4. Monitor user activity

---

## 📚 Struktur Project (Untuk yang Penasaran)

```
avenution/
├── app/               # Logic aplikasi (Controllers, Models, Services)
├── config/            # File konfigurasi
├── database/          # Migrations & Seeders
├── public/            # File publik (index.php, assets compiled)
├── resources/         # Views (Blade), CSS, JS source
├── routes/            # Routing (web.php, api.php)
├── storage/           # File upload, logs, cache
├── .env               # Environment variables (RAHASIA!)
├── composer.json      # PHP dependencies
├── package.json       # JavaScript dependencies
└── artisan            # Laravel CLI tool
```

---

## ✅ Checklist Setup

- [ ] Laragon terinstall dan jalan (lampu hijau)
- [ ] Node.js terinstall
- [ ] `composer install` selesai
- [ ] `npm install` selesai
- [ ] File `.env` sudah ada dan configured
- [ ] `php artisan key:generate` sudah jalan
- [ ] Database `avenution` sudah dibuat
- [ ] `php artisan migrate:fresh --seed` sukses
- [ ] `npm run build` sukses
- [ ] `php artisan serve` jalan
- [ ] Buka `http://localhost:8000` di browser
- [ ] Bisa login dengan admin@avenution.com

---

## 🎓 Tips untuk Pemula

1. **Jangan takut error!** Error adalah guru terbaik. Baca pesan errornya baik-baik.
2. **Google is your friend.** Copy paste error message ke Google.
3. **Terminal itu teman, bukan musuh.** Pelan-pelan aja, satu perintah satu perintah.
4. **Backup sebelum otak-atik.** Copy folder project sebelum eksperimen.
5. **Join komunitas.** Discord/Telegram Laravel Indonesia banyak yang helpful.

---

## 📞 Butuh Bantuan?

- **Laravel Indonesia:** https://t.me/laravelindonesia
- **Documentation:** https://laravel.com/docs/11.x
- **Stack Overflow:** Tag `laravel`

---

**Happy Coding! 🚀**

Project by Avenution Team
