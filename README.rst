--------
Job Demo
--------

A. Paduan untuk menyiapkan sistemnya:

1. buat file .env dengan menyalin dari file .env.example
2. buka file .env dan lihat bagian paling bawah, ada WWWUSER dan WWWGROUP, pastikan
   nilainya sama dengan uid/gid dari user di komputer host (di Linux jalankan
   perintah :code:`id` di terminal).
3. docker compose up

B. Persiapan untuk database dan Laravel:

1. docker compose stop
2. docker compose run --no-deps laravel.test composer install
3. docker compose up
4. ./vendor/bin/sail npm install
5. ./vendor/bin/sail npm run build
6. ./vendor/bin/sail php artisan migrate
7. ./vendor/bin/sail php artisan db:seed
8. ./vendor/bin/sail composer run-script tests

C. Website-nya sekarang bisa diakses di http://127.0.0.1:8080/dashboard


Daftar user yang bisa dipakai untuk login
-----------------------------------------

* admin@ptxyz.com
* admin@ptxyz1.com
* admin@ptxyz2.com
* manager1@ptxyz.com
* supervisor1@ptxyz1.com
* supervisor1@ptxyz2.com
* user1@ptxyz1.com
* user1@ptxyz2.com

Password-nya semua adalah 'pass'.


Catatan penting
---------------

Langkah B.2 beda sendiri karena ketika install modul-modul lewat composer, bisa
jadi file-file Laravel Sail akan ditimpah dengan file baru, akan ada konflik.
