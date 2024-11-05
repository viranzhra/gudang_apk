# Intro
Sistem Manajemen Inventaris Gudang - Barang Masuk dan Barang Keluar

![App Screenshot](https://github.com/viranzhra/gudang_apk/blob/main/dsbAdmin.png)

## Akun Admin Gudang
**email:** admin@gmail.com

**password:** admin

## Akun Customer
**email:** customer@gmail.com

**password:** customer

## Tech Stack

**Client:** Blade Templating Engine, Bootstrap, Jquery, Sweetalert2, Select2, dataTables, Chart.js, tawk.to

**Server:** PHP 8.2.x, Laravel 11.x, PostgreSQL 16.x, RESTful API

**Other:** Composer, Node.js and NPM, Git for version control, Postman for API testing

  
## List Menu

- Dashboard
- Data barang
- Data gudang
- Data supplier
- Data customer
- Data jenis barang
- Data status barang
- Barang masuk
- Permintaan barang keluar
- Data jenis keperluan
- Barang keluar
- Laporan Stok
- Laporan Barang Masuk
- Laporan Barang Keluar
- Manajemen Role (Untuk kelola Role dan Hak Akses)
  
## Installation 

```
composer install or composer update
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=0.0.0.0 --port=8000

npm install
npm run dev
```