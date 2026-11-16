# KADESTIM — Platform Digital Kader Kesehatan Jawa Timur

KADESTIM adalah platform digital untuk mengelola informasi kegiatan Duta Kesehatan Jawa Timur, edukasi kesehatan, galeri dokumentasi, dan media pembelajaran interaktif berbasis skenario.

## Modul
- **Edukasi Kesehatan** — artikel dan materi edukasi.
- **Event Duta** — agenda, kegiatan, dan informasi acara.
- **Galeri** — dokumentasi kegiatan dan kampanye.
- **Media Pembelajaran** — Jelajah Kasus Kesehatan Interaktif dengan pendekatan Scenario-Based Learning.
- **Panel Admin** — login admin, CRUD konten, filter kategori/status, dan alur draft/review/published/archived.

## Teknologi
- Laravel 12 / PHP 8.2+
- MySQL
- Blade, CSS, JavaScript
- Git dan GitHub

## Menjalankan secara lokal
1. Pastikan PHP 8.2+, Composer, MySQL, dan ekstensi PHP yang dibutuhkan Laravel sudah tersedia.
2. Clone repository, lalu jalankan `composer install`.
3. Salin `.env.example` menjadi `.env`.
4. Buat database MySQL bernama `kadestim` (atau sesuaikan nama database di `.env`).
5. Jalankan `php artisan key:generate`.
6. Jalankan migrasi: `php artisan migrate`.
7. Buat akun admin melalui terminal: `php artisan kadestim:create-admin`. Isi nama, email, dan kata sandi saat diminta. Kata sandi minimal 12 karakter.
8. Jalankan server lokal: `php artisan serve`.
9. Buka `http://127.0.0.1:8000`; halaman admin ada di `/admin/login`.

## Alur pengelolaan konten
- Admin login ke `/admin/login`.
- Admin membuka dashboard dan memilih kategori.
- Admin membuat atau mengedit konten.
- Status **Draft** dan **Review** tidak tampil untuk publik.
- Status **Published** tampil di halaman publik.
- Status **Archived** disimpan di area admin dan tidak tampil untuk publik.
- Untuk tipe **Media Pembelajaran**, admin dapat mengisi data skenario dalam JSON: `intro`, `situation`, `supporting_info` (array), `question`, `choices` (array berisi `label`, `feedback`, `is_recommended`), dan `summary`. Pengunjung membaca situasi dan informasi pendukung, memilih tindakan, lalu melihat penjelasan dan rangkuman tanpa sistem skor.

## Keamanan
- Tidak ada pendaftaran admin publik. Akun admin dibuat melalui perintah terminal.
- Akses dashboard dan CRUD dibatasi middleware autentikasi dan role admin.
- Form login dibatasi percobaannya (rate limit).
- Jangan pernah mengunggah file `.env`, password, token, atau data pribadi ke repository.
- Untuk produksi, atur `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, dan kredensial database yang kuat.
- Materi kesehatan perlu ditinjau tenaga/pihak yang kompeten sebelum diterbitkan. Konten KADESTIM bukan diagnosis atau pengganti konsultasi tenaga kesehatan.

## Catatan tahap pengembangan
Fondasi aplikasi, routing, login admin, pengelolaan konten dasar, dan halaman publik sudah disiapkan di repository. Aplikasi belum diuji di server karena memerlukan instalasi dependency, konfigurasi database, dan pengujian runtime pada lingkungan PHP/MySQL.
