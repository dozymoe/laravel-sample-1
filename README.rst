------------
Demo Sample1
------------

Pengantar
---------

Ada beberapa hal yang diperagakan oleh program ini:

* Object Level Permissions, bagaimana menggunakan Laravel model policy untuk
  implementasi hak akses ke data.

* Bagaimana mengkompilasi asset jadi js/css menggunakan Laravel Vite.

* Bagaimana menjalankan tests dan static code analysis.

* Bagaimana menggunakan docker untuk pengerjaan program.


Tentang Website
---------------

Ada 3 Company yang tersusun berdasarkan hirarki (salah satunya adalah perusahaan
induk), dan 4 user role yang tersedia: admin, manager, supervisor, user. 

Supervisor bisa melihat user di Company mereka sendiri, Manager bisa melihat
Supervisor di Company mereka dan sub-Company di bawahnya, Admin bisa melihat dan
mengubah data dari semua role yang ada di Company mereka.


Cara pakai
----------

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
