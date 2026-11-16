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
- Untuk tipe **Media Pembelajaran**, admin menggunakan editor visual: kelompok sasaran, topik, pembuka, situasi, informasi pendukung, pertanyaan refleksi, pilihan tindakan, penjelasan, dan rangkuman. Pengunjung dapat memfilter modul berdasarkan kelompok sasaran dan topik, lalu menjelajahi kasus tanpa sistem skor.

## Keamanan
- Tidak ada pendaftaran admin publik. Akun admin dibuat melalui perintah terminal.
- Akses dashboard dan CRUD dibatasi middleware autentikasi dan role admin.
- Form login dibatasi percobaannya (rate limit).
- Jangan pernah mengunggah file `.env`, password, token, atau data pribadi ke repository.
- Untuk produksi, atur `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, dan kredensial database yang kuat.
- Materi kesehatan perlu ditinjau tenaga/pihak yang kompeten sebelum diterbitkan. Konten KADESTIM bukan diagnosis atau pengganti konsultasi tenaga kesehatan.

## Pemeriksaan otomatis
GitHub Actions menjalankan `php artisan test` setiap ada push ke `main` atau pull request. Status workflow dapat dilihat pada tab **Actions** repository. Tetap lakukan pemeriksaan manual pada lingkungan hosting sebelum mengumumkan situs aktif.

## Persiapan publikasi
1. Siapkan hosting yang mendukung PHP 8.2+, Composer, ekstensi Laravel, dan MySQL/MariaDB.
2. Arahkan document root domain ke folder `public` aplikasi Laravel.
3. Atur `.env` produksi: `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL` sesuai domain, dan kredensial database produksi.
4. Jalankan `composer install --no-dev --optimize-autoloader`, `php artisan key:generate` hanya untuk instalasi baru, `php artisan migrate --force`, dan `php artisan config:cache`.
5. Buat akun admin melalui `php artisan kadestim:create-admin` di terminal server.
9. Aktifkan HTTPS, pastikan folder `storage` dan `bootstrap/cache` dapat ditulis, lalu cek `/up`, login admin, seluruh halaman publik, unggah gambar, dan publikasi konten.
10. Jangan unggah `.env`, kredensial, atau data pribadi ke GitHub.

## Editor media pembelajaran
Admin dapat menyusun modul Jelajah Kasus melalui form visual tanpa menulis JSON:
- Pembuka dan situasi kasus.
- Informasi pendukung yang dapat ditambah/hapus.
- Pertanyaan refleksi.
- Dua sampai enam pilihan tindakan, masing-masing dengan penjelasan.
- Tepat satu pilihan ditandai sebagai tindakan yang direkomendasikan.
- Rangkuman pembelajaran.
- Pratinjau interaktif sebelum konten disimpan.

Dashboard admin menampilkan jumlah total konten, konten terbit, draft, review, jumlah per kategori, serta enam konten yang terakhir diperbarui.

## Pengujian
Jalankan `php artisan test` untuk menjalankan automated tests. Pengujian mencakup akses halaman publik, autentikasi admin, penyimpanan skenario pembelajaran, dan validasi pilihan yang direkomendasikan. Hasil pengujian tetap perlu dijalankan di lingkungan PHP/MySQL proyek sebelum deployment.
