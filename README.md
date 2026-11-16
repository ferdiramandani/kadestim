# KADESTIM — Platform Digital Kader Kesehatan Jawa Timur

KADESTIM adalah platform digital untuk mengelola informasi kegiatan Duta Kesehatan Jawa Timur, edukasi kesehatan, galeri dokumentasi, dan media pembelajaran interaktif berbasis skenario.

## Modul utama
- **Edukasi Kesehatan** — artikel dan materi edukasi yang dapat ditinjau sebelum diterbitkan.
- **Event Duta** — informasi kegiatan, agenda, dan detail acara.
- **Galeri** — dokumentasi kegiatan dan kampanye kesehatan.
- **Media Pembelajaran** — Jelajah Kasus Kesehatan Interaktif dengan pendekatan Scenario-Based Learning.
- **Panel Admin** — pengelolaan konten dengan status draft, review, published, dan archived.

## Teknologi
- Laravel 12 / PHP 8.2+
- MySQL
- Blade, CSS, dan JavaScript
- Git dan GitHub

## Menjalankan secara lokal
1. Pastikan PHP 8.2+, Composer, dan MySQL sudah terpasang.
2. Jalankan `composer install`.
3. Salin `.env.example` menjadi `.env`.
4. Sesuaikan konfigurasi database pada `.env`.
5. Jalankan `php artisan key:generate`.
6. Jalankan migrasi dengan `php artisan migrate`.
7. Jalankan aplikasi menggunakan `php artisan serve`.

## Catatan keamanan
- Jangan pernah mengunggah file `.env`, kata sandi, token, atau data pribadi ke repository.
- Akun admin dibuat dan dikelola oleh pengelola yang berwenang; pengunjung tidak dapat mendaftarkan diri sebagai admin.
- Materi kesehatan harus melalui peninjauan pihak yang kompeten sebelum dipublikasikan.
- Konten edukasi bukan pengganti diagnosis atau konsultasi tenaga kesehatan.

## Status pengembangan
Tahap awal: fondasi aplikasi dan struktur modul KADESTIM.
