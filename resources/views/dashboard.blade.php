@extends('layouts.navigation')

@section('content')
    <style>
        /* Scrollbar custom */
        ::-webkit-scrollbar {
            -webkit-appearance: none;
            width: 3px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background-color: transparent
        }

        ::-webkit-scrollbar-thumb {
            background-color: rgb(99 91 255 / 50%);
            border-radius: 10px
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: rgb(99 91 255 / 75%)
        }

        ::-webkit-scrollbar-thumb:active {
            background-color: rgb(99 91 255 / 75%)
        }

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
            white-space: nowrap;
            /* Mencegah teks melompat ke baris baru */
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
            justify-content: space-between;
            /* Pastikan elemen berada di sisi yang berlawanan */
            align-items: center;
            margin-bottom: 15px;
        }

        .search-box {
            justify-content: right;
            margin-right: 0;
            /* display: flex; */
            /* align-items: center; */
            position: relative;
            /* Tambahkan ini */
            width: 250px;
            /* Setel lebar untuk menjaga ukuran input */
        }

        .search-box input[type="search"] {
            padding: 5px 30px 5px 10px;
            /* Tambahkan padding untuk memberikan ruang bagi ikon */
            width: 100%;
            /* Gunakan 100% lebar dari kontainer .search-box */
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            /* Pastikan padding dan border dihitung dalam lebar elemen */
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
            gap: 10px;
            /* Jarak antar tombol */
        }

        .nav-btn {
            display: inline-block;
            padding: 10px;
            border-radius: 2px;
            color: #1855bf;
            /* Warna teks default */
            background-color: transparent;
            /* Latar belakang transparan */
            text-decoration: none;
            text-align: center;
            font-size: 15px;
            cursor: pointer;
            transition: background-color 0.3s, color 0.3s, border-bottom 0.3s;
            /* Tambahkan transisi untuk border-bottom */
            margin: 0;
            /* Hapus jarak antar tombol */
        }

        /* Latar belakang saat hover */
        /* .nav-btn:hover {
                                            background-color: #e7ebec;
                                            } */

        .nav-btn.active {
            color: #007bff;
            font-weight: bold;
            border-bottom: 2px solid #007bff;
            /* Garis di bawah tombol aktif */
        }

        .rows-dropdown {
            display: flex;
            align-items: center;
            gap: 5px;
            /* Jarak antara dropdown dan label */
            margin-left: 220px;
            /* Menambahkan jarak kiri agar lebih dekat dengan kotak pencarian */
            color: #5a5c69;
        }
    </style>

    <div class="row" style="margin-top: 20px;margin-bottom:-16px">
        {{-- <div class="card" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem; height: 85px;">
            <div class="card-body">
                <h4 style="color: #5a5c69; font-size: 20px;">Hallo, {{ Auth::user()->name }}!</h4>
            </div>
        </div> --}}

        <!-- Barang -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-6 mb-4">
                        <span class="round-48 d-flex align-items-center justify-content-center rounded bg-danger-subtle">
                            <iconify-icon icon="solar:box-linear" class="fs-6 text-danger"></iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4">Barang</h6>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ $total_barang }}</h3>
                            <span class="fs-11 text-success fw-semibold d-none">+18%</span>
                        </div>
                        <div class="col-6">
                            <div id="total-barang"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Masuk -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-6 mb-4">
                        <span class="round-48 d-flex align-items-center justify-content-center rounded bg-warning-subtle">
                            <iconify-icon icon="solar:inbox-in-linear" class="fs-6 text-warning"></iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4">Barang Masuk</h6>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ $total_barang_masuk }}</h3>
                            <span class="fs-11 text-success fw-semibold d-none">+18%</span>
                        </div>
                        <div class="col-6">
                            <div id="total-barangmasuk"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Keluar -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-6 mb-4">
                        <span class="round-48 d-flex align-items-center justify-content-center rounded bg-success-subtle">
                            <iconify-icon icon="solar:inbox-out-linear" class="fs-6 text-success"></iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4">Barang Keluar</h6>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ $total_barang_keluar }}</h3>
                            <span class="fs-11 text-success fw-semibold d-none">+18%</span>
                        </div>
                        <div class="col-6">
                            <div id="total-barangkeluar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permintaan Barang Keluar -->
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-6 mb-4">
                        <span class="round-48 d-flex align-items-center justify-content-center rounded bg-primary-subtle">
                            <iconify-icon icon="solar:notification-unread-lines-broken"
                                class="fs-6 text-primary"></iconify-icon>
                        </span>
                        <h6 class="mb-0 fs-4">Permintaan</h6>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <h3>{{ $total_permintaan }}</h3>
                            <span class="fs-11 text-success fw-semibold d-none">+18%</span>
                        </div>
                        <div class="col-6">
                            <div id="total-permintaan"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">

        <!-- Grafik Barang Masuk -->
        <div class="col-lg-8 d-flex align-items-strech">
            <div class="card w-100" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Incoming & Outbound Item</h5>
                        </div>
                        {{-- <div>
                            <select class="form-select">
                                <option value="1">March 2024</option>
                                <option value="2">April 2024</option>
                                <option value="3">May 2024</option>
                                <option value="4">June 2024</option>
                            </select>
                        </div> --}}
                    </div>
                    <div id="incoming-item"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="mb-4">
                        <h5 class="card-title fw-semibold">Daily activities</h5>
                    </div>
                    <div style="max-height: 300px; overflow-y:auto">
                        <ul class="timeline-widget mb-0 position-relative mb-n5">
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time mt-n1 text-muted flex-shrink-0 text-end">09:46
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge bg-primary flex-shrink-0 mt-2"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n1">Payment received from John
                                    Doe of $385.90</div>
                            </li>
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time mt-n6 text-muted flex-shrink-0 text-end">09:46
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge bg-warning flex-shrink-0"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n6 fw-semibold">New sale
                                    recorded <a href="javascript:void(0)"
                                        class="text-primary d-block fw-normal ">#ML-3467</a>
                                </div>
                            </li>
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time mt-n6 text-muted flex-shrink-0 text-end">09:46
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge bg-warning flex-shrink-0"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n6">Payment was made of $64.95
                                    to Michael</div>
                            </li>
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time mt-n6 text-muted flex-shrink-0 text-end">09:46
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge bg-secondary flex-shrink-0"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n6 fw-semibold">New sale
                                    recorded <a href="javascript:void(0)"
                                        class="text-primary d-block fw-normal ">#ML-3467</a>
                                </div>
                            </li>
                            <li class="timeline-item d-flex position-relative overflow-hidden">
                                <div class="timeline-time mt-n6 text-muted flex-shrink-0 text-end">09:46
                                </div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge bg-primary flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n6">Payment received from John
                                    Doe of $385.90</div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- <!-- Grafik Barang Keluar -->
        <div class="col-lg-6 d-flex align-items-strech">
            <div class="card w-100" style="border-radius: 20px !important">
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
        </div> --}}
    </div>

    <div class="card" style="border-radius: 20px !important">
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

            ////////

            // Data dari controller (dikirim ke Blade)
            let dates = @json($dates);
            let countsBarang = @json($counts_barang);
            let countsBarangMasuk = @json($counts_barang_masuk);
            let countsBarangKeluar = @json($counts_barang_keluar);
            let countsPermintaan = @json($counts_permintaan);

            /* Barang */
            var barang = {
                chart: {
                    id: "sparkline1",
                    type: "line",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                    group: "sparkline1",
                },
                series: [{
                    name: "Barang",
                    color: "var(--bs-danger)",
                    data: countsBarang, // Data dari API
                }],
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                markers: {
                    size: 0,
                },
                tooltip: {
                    theme: "dark",
                    fixed: {
                        enabled: true,
                        position: "right",
                    },
                    x: {
                        formatter: function(val, opts) {
                            // Menampilkan tanggal pada tooltip
                            return dates[opts.dataPointIndex]; // Ambil tanggal dari array dates
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#total-barang"), barang).render();

            /* Barang Masuk */
            var barangmasuk = {
                chart: {
                    id: "sparkline2",
                    type: "line",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                    group: "sparkline2",
                },
                series: [{
                    name: "Barang Masuk",
                    color: "var(--bs-warning)",
                    data: countsBarangMasuk, // Data dari API
                }],
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                markers: {
                    size: 0,
                },
                tooltip: {
                    theme: "dark",
                    fixed: {
                        enabled: true,
                        position: "right",
                    },
                    x: {
                        formatter: function(val, opts) {
                            // Menampilkan tanggal pada tooltip
                            return dates[opts.dataPointIndex]; // Ambil tanggal dari array dates
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#total-barangmasuk"), barangmasuk).render();

            /* Barang Keluar */
            var barangkeluar = {
                chart: {
                    id: "sparkline3",
                    type: "line",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                    group: "sparkline3",
                },
                series: [{
                    name: "Barang Keluar",
                    color: "var(--bs-success)",
                    data: countsBarangKeluar, // Data dari API
                }],
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                markers: {
                    size: 0,
                },
                tooltip: {
                    theme: "dark",
                    fixed: {
                        enabled: true,
                        position: "right",
                    },
                    x: {
                        formatter: function(val, opts) {
                            // Menampilkan tanggal pada tooltip
                            return dates[opts.dataPointIndex]; // Ambil tanggal dari array dates
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#total-barangkeluar"), barangkeluar).render();

            /* Permintaan Barang */
            var permintaan = {
                chart: {
                    id: "sparkline4",
                    type: "line",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 60,
                    sparkline: {
                        enabled: true,
                    },
                    group: "sparkline4",
                },
                series: [{
                    name: "Permintaan",
                    color: "var(--bs-primary)",
                    data: countsPermintaan, // Data dari API
                }],
                stroke: {
                    curve: "smooth",
                    width: 2,
                },
                markers: {
                    size: 0,
                },
                tooltip: {
                    theme: "dark",
                    fixed: {
                        enabled: true,
                        position: "right",
                    },
                    x: {
                        formatter: function(val, opts) {
                            // Menampilkan tanggal pada tooltip
                            return dates[opts.dataPointIndex]; // Ambil tanggal dari array dates
                        }
                    },
                    y: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return Math.round(val);
                        }
                    }
                }
            };
            new ApexCharts(document.querySelector("#total-permintaan"), permintaan).render();

            ////////////////////

            // -----------------------------------------------------------------------
            // Subscriptions
            // -----------------------------------------------------------------------

            let months = @json($months);
            let countsBarangMasuk6Bulan = @json($counts_barang_masuk_6months);
            let countsBarangKeluar6Bulan = @json($counts_barang_keluar_6months);

            var chart = {
                series: [{
                        name: "Masuk",
                        data: countsBarangMasuk6Bulan.map(count => count === 0 ? Math.floor(Math.random() *
                            10) + 1 : count),
                    },
                    {
                        name: "Keluar",
                        data: countsBarangKeluar6Bulan.map(count => count === 0 ? -Math.floor(Math
                        .random() * 10) - 1 : -count),
                    },
                ],
                chart: {
                    toolbar: {
                        show: false,
                    },
                    type: "bar",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 270,
                    stacked: true,
                    offsetX: -15,
                },
                colors: ["var(--bs-primary)", "var(--bs-danger)"],
                plotOptions: {
                    bar: {
                        horizontal: false,
                        barHeight: "60%",
                        columnWidth: "15%",
                        borderRadius: [6],
                        borderRadiusApplication: "end",
                        borderRadiusWhenStacked: "all",
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                legend: {
                    show: false,
                },
                grid: {
                    show: true,
                    padding: {
                        top: 0,
                        bottom: 0,
                        right: 0,
                    },
                    borderColor: "rgba(0,0,0,0.05)",
                    xaxis: {
                        lines: {
                            show: true,
                        },
                    },
                    yaxis: {
                        lines: {
                            show: true,
                        },
                    },
                },
                yaxis: {
                    min: -5,
                    max: 5,
                },
                xaxis: {
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    categories: months.map(month => {
                        const date = new Date(month);
                        return date.toLocaleString('default', {
                            month: 'short'
                        });
                    }),
                    labels: {
                        style: {
                            fontSize: "13px",
                            colors: "#adb0bb",
                            fontWeight: "400"
                        },
                    },
                },
                yaxis: {
                    tickAmount: 4,
                },
                tooltip: {
                    theme: "dark",
                },
            };

            var chart = new ApexCharts(
                document.querySelector("#incoming-item"),
                chart
            );
            chart.render();
        });
    </script>


    <script>
        function checkSerialNumber() {
            fetch('/serialnumber', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert('Error: ' + data.error);
                    } else {
                        console.log('Serial numbers:', data);
                        const serialNumbers = [100300, 100400];
                        const foundSerialNumbers = data.filter(item => serialNumbers.includes(item.serial_number));
                        if (foundSerialNumbers.length > 0) {
                            console.log('SN Ditemukan: ' + foundSerialNumbers.map(item => item.serial_number).join(
                                ', '));
                        } else {
                            console.log('No matching serial numbers found.');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while checking the serial numbers.');
                });
        }
        //setTimeout(() => {checkSerialNumber()}, 1000);
    </script>
@endsection
