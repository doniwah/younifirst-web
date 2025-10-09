# Younifirst Web

Ini adalah proyek website Younifirst, sebuah aplikasi web yang dibangun menggunakan PHP.

## Deskripsi

Proyek ini merupakan aplikasi web sederhana yang dibuat sebagai bagian dari studi kasus atau pembelajaran. Aplikasi ini memiliki sistem routing sendiri, struktur file yang terorganisir dengan baik, dan menggunakan Composer untuk mengelola dependensi.

## Persyaratan

- PHP 8.0 atau lebih tinggi

## Instalasi

1.  Clone repositori ini ke mesin lokal Anda.
2.  Buka terminal di direktori root proyek.
3.  Jalankan perintah berikut untuk menginstal dependensi yang diperlukan:

    ```bash
    composer install
    ```

## Menjalankan Aplikasi

Aplikasi ini dapat dijalankan menggunakan server web bawaan PHP.

1.  Buka terminal di dalam direktori `public`.
2.  Jalankan perintah berikut:

    ```bash
    php -S localhost:8000
    ```

3.  Buka browser Anda dan kunjungi `http://localhost:8000`.

## Menjalankan Tes

Proyek ini menggunakan PHPUnit untuk pengujian. Untuk menjalankan tes, jalankan perintah berikut dari direktori root proyek:

```bash
./vendor/bin/phpunit
```

## Struktur Folder

-   `/app`: Berisi logika inti aplikasi, termasuk Router, Controller, dan View.
-   `/public`: Berisi file yang dapat diakses publik dan titik masuk utama aplikasi (`index.php`).
-   `/vendor`: Berisi dependensi proyek yang dikelola oleh Composer.
-   `/tests`: Berisi file-file pengujian untuk aplikasi.

## Penulis

-   doniwah (whyddoni@gmail.com)
