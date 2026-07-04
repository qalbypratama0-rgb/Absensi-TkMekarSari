# Sistem Absensi QR Code Sekolah (Admin-Centric)

Sistem absensi berbasis web menggunakan QR Code untuk mempermudah pencatatan kehadiran siswa. Admin dapat mengelola kelas, data siswa, laporan absensi, dan melakukan input manual. Kiosk Scanner disediakan untuk pemindaian kartu/kode QR siswa.

## Tech Stack

- **Framework:** Laravel 10.x
- **Database:** MySQL
- **Frontend CSS:** Tailwind CSS (via Laravel Breeze)
- **Frontend JS:** Alpine.js / Vite
- **Autentikasi:** Laravel Breeze (Blade Stack)
- **Tema UI:** Modern Clean Light Mode (Aksen Biru Profesional, Background `bg-gray-50`)

---

## Skema Database (Database Schema)

Sistem menggunakan database `absen_qr` dengan struktur tabel sebagai berikut:

### 1. Tabel `kelas`
Menyimpan data kelas sekolah.
- `id` (BigInt, Primary Key, Auto Increment)
- `nama_kelas` (String)
- `timestamps` (`created_at`, `updated_at`)

### 2. Tabel `siswa`
Menyimpan data identitas siswa.
- `id` (BigInt, Primary Key, Auto Increment)
- `nis` (String, Unique)
- `nama_siswa` (String)
- `jk` (Enum: `L` / `P`)
- `kelas_id` (Foreign Key -> `kelas.id`, Cascade on Delete)
- `timestamps` (`created_at`, `updated_at`)

### 3. Tabel `absensi`
Mencatat riwayat log absensi siswa.
- `id` (BigInt, Primary Key, Auto Increment)
- `siswa_id` (Foreign Key -> `siswa.id`, Cascade on Delete)
- `waktu_hadir` (DateTime)
- `status` (Enum: `Hadir`, `Terlambat`, `Sakit`, `Izin`, `Alpha`, `Hadir Manual`)
- `timestamps` (`created_at`, `updated_at`)

### 4. Tabel `pengaturan`
Menyimpan konfigurasi sistem (misal: batas jam terlambat, nama sekolah, dll).
- `id` (BigInt, Primary Key, Auto Increment)
- `key` (String, Unique)
- `value` (Text, Nullable)
- `timestamps` (`created_at`, `updated_at`)

---

## Struktur Routing

### Web Routes (`routes/web.php`)

- **Rute Publik:**
  - `GET /scanner` (`route('scanner')`) -> Halaman Kiosk Pemindaian QR Code.
- **Rute Panel Admin (Diproteksi Middleware `auth` & `verified`):**
  - `GET /dashboard` (`route('dashboard')`) -> Ringkasan statistik kehadiran hari ini.
  - `GET /kelas` (`route('kelas.index')`) -> Pengelolaan data kelas.
  - `GET /siswa` (`route('siswa.index')`) -> Pengelolaan data murid (siswa).
  - `GET /generate-qr` (`route('generate-qr.index')`) -> Halaman untuk generate QR Code murid.
  - `GET /cetak-lanyard` (`route('cetak-lanyard.index')`) -> Halaman cetak lanyard kartu murid.
  - `GET /laporan` (`route('laporan.index')`) -> Halaman laporan absensi (dengan filter tanggal, kelas, dan visualisasi ringkasan statistik). Dilayani oleh `LaporanController@index`.
  - `PATCH /laporan/update-status/{absensi}` (`route('laporan.updateStatus')`) -> Endpoint untuk mengubah status kehadiran siswa secara manual. Dilayani oleh `LaporanController@updateStatus`.
  - `GET /input-manual` (`route('absensi.manual')`) -> Input kehadiran siswa secara manual oleh admin.
  - `GET /pengaturan` (`route('pengaturan.index')`) -> Konfigurasi sistem absensi.
  - `GET/PATCH/DELETE /profile` -> Rute profil akun admin.

### API Routes (`routes/api.php`)

- **POST `/api/absen`** (`route('api.absen')`) -> Endpoint penerima data hasil scan kartu QR untuk diproses menjadi log absensi siswa.

---

## Cara Instalasi & Menjalankan Aplikasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di komputer lokal:

### Prasyarat (Prerequisites)
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL Server

### Langkah-langkah (Steps)

1. **Clone/Buka Folder Proyek**
   Masuk ke direktori utama proyek.

2. **Instal Dependensi PHP**
   ```bash
   composer install
   ```

3. **Instal Dependensi Node.js**
   ```bash
   npm install
   ```

4. **Konfigurasi Environment**
   Salin file `.env.example` menjadi `.env` (sudah dikonfigurasi menggunakan nama database `absen_qr`):
   ```bash
   cp .env.example .env
   # Generate key aplikasi jika belum ada
   php artisan key:generate
   ```

5. **Nyalakan MySQL Server & Buat Database**
   Pastikan MySQL berjalan di lokal Anda, kemudian buat database bernama `absen_qr`.

6. **Jalankan Migrasi Database**
   ```bash
   php artisan migrate
   ```

7. **Compile Assets (CSS & JS)**
   ```bash
   npm run build
   # Atau untuk mode development:
   npm run dev
   ```

8. **Jalankan Server Laravel**
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

---

## Fitur Utama

1. **Dashboard Interaktif:** Menampilkan statistik real-time kehadiran hari ini (Total Siswa, Hadir, Terlambat, Izin/Sakit, Alpha) serta 10 riwayat pemindaian terbaru.
2. **Data Master (CRUD):** Pengelolaan data Kelas dan Murid (Siswa) menggunakan modal box interaktif berbasis Alpine.js tanpa muat ulang halaman (page refresh).
3. **Kiosk Scanner:** Halaman pemindaian QR Code layar penuh dengan jam digital, webcam scanner, feedback suara synthesizer (Web Audio API), dan notifikasi SweetAlert2.
4. **Download & Cetak Lanyard:**
   - Unduh kode QR siswa dalam format PNG (berbasis ekstensi GD).
   - Cetak kartu nama/lanyard siswa dengan desain modern. Otomatis menyembunyikan navigasi halaman saat dicetak.
5. **Import Siswa:** Pengunggahan daftar siswa secara massal menggunakan file Excel/CSV (menggunakan format `nis | nama_siswa | jk | nama_kelas`). Kelas baru otomatis dibuat jika belum terdaftar.
6. **Task Scheduling (Otomatisasi Alpha):** Perintah Artisan `absen:auto-alpha` yang dijadwalkan berjalan otomatis setiap jam 15:00 untuk menandai siswa yang tidak hadir dengan status Alpha.
