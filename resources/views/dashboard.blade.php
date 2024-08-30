@extends('layouts.navigation')

@section('content')

<style>
    .card {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
    }

    .container {
      width: 80%;
      margin: 50px auto;
      background-color: #ffffff;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #333333;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
        color: black
    }

        th {
            background-color: #f2f2f2;
            cursor: default;
            font-weight: bold;
            color: rgba(0, 0, 0, 0.829);
        }

    .status {
      font-weight: 500;
      padding: 5px 10px;
      border-radius: 5px;
      text-align: center;
      white-space: nowrap; /* Mencegah teks melompat ke baris baru */
    }

    .status.in-stock {
      background-color: #d4edda;
      color: #155724;
    }

    .status.out-of-stock {
      background-color: #f8d7da;
      color: #721c24;
    }

    .status.limited {
      background-color: #fff3cd;
      color: #856404;
    }

    /* css untuk ringkasan inventaris */
    .card {
        border-radius: 8px;
        transition: transform 0.2s, box-shadow 0.2s;
        padding-left: 15px;
    }

    .card:hover {
        transform: none;
    }

    .card .card-body {
        position: relative;
        padding: 10px;
    }

    .card .card-body::before {
        content: "";
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        z-index: -1;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.15) 0%, rgba(255, 255, 255, 0.15) 100%);
        border-radius: 8px;
        transition: opacity 0.2s;
        opacity: 0;
    }

    .card:hover .card-body::before {
        opacity: 1;
    }

    .text-primary {
        color: #635bff !important;
    }

    .text-gray-800 {
        color: #5a5c69 !important;
    }

    .text-uppercase {
        text-transform: uppercase;
    }

    .font-weight-bold {
        font-weight: 700 !important;
    }

    .shadow {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .border-left-primary {
        border-left: 0.25rem solid #635bff !important;
    }

    .h5 {
        font-size: 1.25rem;
    }

    .mb-1 {
        margin-bottom: 0.25rem !important;
    }

    .mb-4 {
        margin-bottom: 1.5rem !important;
    }

    .py-2 {
        padding-top: 0.5rem !important;
        padding-bottom: 0.5rem !important;
    }

    .text-xs {
        font-size: 0.875rem;
    }

    .fa-2x {
        font-size: 2em;
    }

    .text-gray-300 {
        color: #d1d3e2 !important;
    }

        /* search */
        .search-container {
        display: flex;
        justify-content: space-between; /* Pastikan elemen berada di sisi yang berlawanan */
        align-items: center;
        margin-bottom: 15px;
        }

        .search-box {
        justify-content: right;
        margin-right: 0;
        /* display: flex; */
        /* align-items: center; */
        position: relative; /* Tambahkan ini */
        width: 250px; /* Setel lebar untuk menjaga ukuran input */
        }

        .search-box input[type="search"] {
        padding: 5px 30px 5px 10px; /* Tambahkan padding untuk memberikan ruang bagi ikon */
        width: 100%; /* Gunakan 100% lebar dari kontainer .search-box */
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box; /* Pastikan padding dan border dihitung dalam lebar elemen */
        }

            .search-box .search-icon {
                position: absolute;
                right: 5px;
                /* background-color: rgb(197, 197, 197); */
                padding: 5px;
                font-size: 18px;
                border-radius: 4px;
                color: gray;
                cursor: pointer;
                top: 5px;
            }

            .search-container label {
                margin-right: 5px;
            }

            .search-container select {
                padding: 5px;
                border: 1px solid #ccc;
                border-radius: 4px;
            }

            /* navigator riwayat */
            .navigation-buttons {
            display: flex;
            gap: 10px; /* Jarak antar tombol */
            }

            .nav-btn {
            display: inline-block;
            padding: 10px;
            border-radius: 2px;
            color: #1855bf; /* Warna teks default */
            background-color: transparent; /* Latar belakang transparan */
            text-decoration: none;
            text-align: center;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, border-bottom 0.3s; /* Tambahkan transisi untuk border-bottom */
            margin: 0; /* Hapus jarak antar tombol */
            }

            /* Latar belakang saat hover */
            /* .nav-btn:hover {
            background-color: #e7ebec; 
            } */

            .nav-btn.active {
            color: #007bff;
            font-weight: bold;
            border-bottom: 2px solid #007bff; /* Garis di bawah tombol aktif */
            }

        .rows-dropdown {
            display: flex;
            align-items: center;
            gap: 5px; /* Jarak antara dropdown dan label */
            margin-left: 220px; /* Menambahkan jarak kiri agar lebih dekat dengan kotak pencarian */
            color: #5a5c69;
        }
</style>

<div class="row" style="margin-top: 20px;">
  <div class="card" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem; height: 85px;">
    <div class="card-body">
      <h4 style="color: #5a5c69; font-size: 20px;">Hallo, Selamat Datang {{ Auth::user()->name }}!</h4>
    </div>
  </div>
  <!-- Total Supplier -->
  <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          Total Supplier
                      </div>
                      <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{ $total_supplier }}
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-boxes fa-2x text-gray-300"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Total Barang -->
  <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          Total Barang
                      </div>
                      <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{ $total_barang }}
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Total Barang Masuk -->
  <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          Total Barang Masuk
                      </div>
                      <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{ $total_barang_masuk }}
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-calendar fa-2x text-gray-300"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>

  <!-- Total Barang Keluar -->
  <div class="col-xl-3 col-md-6 mb-3">
      <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
              <div class="row no-gutters align-items-center">
                  <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                          Total Barang Keluar
                      </div>
                      <br>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          {{ $total_barang_keluar }}
                      </div>
                  </div>
                  <div class="col-auto">
                      <i class="fas fa-boxes fa-2x text-gray-300"></i>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>

<div class="row" style="margin-top: 20px;">

    <!-- Grafik Barang Masuk -->
    <div class="col-lg-6 d-flex align-items-strech">
      <div class="card w-100" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem;">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">Incoming Item</h5>
            </div>
            <div>
              <select class="form-select">
                <option value="1">March 2024</option>
                <option value="2">April 2024</option>
                <option value="3">May 2024</option>
                <option value="4">June 2024</option>
              </select>
            </div>
          </div>
          <div id="revenue-forecast"></div>
        </div>
      </div>
    </div>

    <!-- Grafik Barang Keluar -->
    <div class="col-lg-6 d-flex align-items-strech">
      <div class="card w-100" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem;">
        <div class="card-body">
          <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
            <div class="mb-3 mb-sm-0">
              <h5 class="card-title fw-semibold">Outbound Item</h5>
            </div>
            <div>
              <select class="form-select">
                <option value="1">March 2024</option>
                <option value="2">April 2024</option>
                <option value="3">May 2024</option>
                <option value="4">June 2024</option>
              </select>
            </div>
          </div>
          <div id="revenue-forecast"></div>
        </div>
      </div>
    </div>
</div>

<div class="card" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem;">
  <div class="card-body">
    <h4 class="mb-4" style="color: #8a8a8a;">Item History</h4>
    <div class="search-container">
      <div class="navigation-buttons">
        <a href="#riwayat-masuk" class="nav-btn" id="btn-masuk">Incoming Item</a>
        <a href="#riwayat-keluar" class="nav-btn" id="btn-keluar">Outbound Item</a>
      </div>
      
      <div class="rows-dropdown">
        <label for="rows-per-page">Show</label>
        <select id="rows-per-page" style="color: #8a8a8a;">
          <option value="5">5 rows</option>
          <option value="10">10 rows</option>
          <option value="20">20 rows</option>
          <option value="50">50 rows</option>
        </select>
        <label for="rows-per-page"></label>
      </div>
      <div class="search-box">
        <input type="search" id="search-input" placeholder="Search...">
        <iconify-icon icon="carbon:search" class="search-icon"></iconify-icon>
      </div>
    </div>
    
    <div id="riwayat-masuk" class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>001</td>
            <td>Barang A</td>
            <td>Kategori 1</td>
            <td>150</td>
            <td><span class="status in-stock">Tersedia</span></td>
          </tr>
          <!-- Tambahkan data riwayat masuk di sini -->
        </tbody>
      </table>
    </div>
    <div id="riwayat-keluar" class="table-responsive" style="display: none;">
      <table class="table">
        <thead>
          <tr>
            <th>ID Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>002</td>
            <td>Barang B</td>
            <td>Kategori 2</td>
            <td>0</td>
            <td><span class="status out-of-stock">Habis</span></td>
          </tr>
          <!-- Tambahkan data riwayat keluar di sini -->
        </tbody>
      </table>
    </div>
  </div>
</div>


  <script>
    document.addEventListener('DOMContentLoaded', function() {
    const btnMasuk = document.getElementById('btn-masuk');
    const btnKeluar = document.getElementById('btn-keluar');
    const riwayatMasuk = document.getElementById('riwayat-masuk');
    const riwayatKeluar = document.getElementById('riwayat-keluar');

  btnMasuk.addEventListener('click', function() {
    riwayatMasuk.style.display = 'block';
    riwayatKeluar.style.display = 'none';
    btnMasuk.classList.add('active');
    btnKeluar.classList.remove('active');
  });

  btnKeluar.addEventListener('click', function() {
    riwayatMasuk.style.display = 'none';
    riwayatKeluar.style.display = 'block';
    btnKeluar.classList.add('active');
    btnMasuk.classList.remove('active');
  });
});

</script>

@endsection