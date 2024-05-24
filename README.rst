--------
Job Demo
--------

Paduan untuk menyiapkan sistemnya:

1. buat file .env dengan menyalin dari file .env.example
2. buka file .env dan lihat bagian paling bawah, ada WWWUSER dan WWWGROUP, pastikan
   nilainya sama dengan uid/gid dari user di komputer host (di Linux jalankan
   perintah :code:`id` di terminal).
3. docker compose up

Persiapan untuk database dan Laravel:

1. docker compose exec --user sail laravel.test composer install
2. docker compose exec --user sail laravel.test npm install
3. docker compose exec --user sail laravel.test npm run build
4. docker compose exec --user sail laravel.test php artisan migrate
5. docker compose exec --user sail laravel.test php artisan db:seed
6. docker compose exec --user sail laravel.test composer run-script tests

Website-nya sekarang bisa diakses di http://127.0.0.1:8080/dashboard

Daftar user yang bisa dipakai untuk login (password-nya semua adalah 'pass'):

* admin@ptxyz.com
* admin@ptxyz1.com
* admin@ptxyz2.com
* manager1@ptxyz.com
* supervisor1@ptxyz1.com
* supervisor1@ptxyz2.com
* user1@ptxyz1.com
* user1@ptxyz2.com
