## Deskripsi Proyek

Proyek ini adalah API untuk mengelola data kelas, peserta, dan kategori kelas dengan sistem registrasi dan login untuk admin menggunakan JWT. Selain itu, API ini dilengkapi dengan operasi CRUD (Create, Read, Update, Delete) untuk entitas Kategori Kelas, Kelas, Peserta, dan Akses Kelas Peserta. API juga menggunakan teknologi cache untuk meningkatkan performa aplikasi. API ini juga sudah di hosting dengan endpoint yang tertera pada akhir readme.

- Registrasi dan Login Admin dengan JWT
- CRUD Kategori Kelas (Membuat, Membaca, Memperbarui, Menghapus)
- CRUD Kelas
- CRUD Peserta
- CRUD Akses Kelas Peserta
- Implementasi Cache menggunakan Database untuk meningkatkan performa aplikasi

## Teknologi yang Digunakan

- Bahasa Pemrograman: PHP
- Framework: Laravel
- Database: MySQL
- Sistem Autentikasi: JSON Web Tokens (JWT)
- Cache: Database

## Cara Menjalankan Proyek

- Clone repositori ini ke lokal Anda.
- Jalankan composer install untuk menginstal semua dependensi.
- Buat file .env dan sesuaikan pengaturan yang dibutuhkan seperti nama database, port, dan pengaturan cache.
- Jalankan php artisan migrate untuk membuat tabel pada database.
- Jalankan php artisan db:seed untuk memasukkan data awal ke dalam tabel.
- Jalankan php artisan serve untuk menjalankan aplikasi.

### Cara Penggunaan

Postman : https://documenter.getpostman.com/view/19502990/2s93CGRvWX
