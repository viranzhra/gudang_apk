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

    <link href="https://bootstrapdemos.adminmart.com/matdash/dist/assets/css/styles.css" rel="stylesheet">

    <!-- Toast -->
    <div class="toast toast-onload align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="toast-body hstack align-items-start gap-6">
            <div>
                <h5 class="text-white fs-3 mb-1">Welcome back <b>{{ session('user_name') }}</b>!</h5>
                <h6 class="text-white fs-2 mb-0">Have a bright day! Remember, you're capable of amazing things.</h6>
            </div>
            <button type="button" class="btn-close btn-close-white fs-2 m-0 ms-auto shadow-none" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>

    <div class="row" style="margin-top:-70px"> {{-- style="margin-top:-70px" --}}
        <div class="col-lg-5">
            <!-- -------------------------------------------- -->
            <!-- Welcome Card -->
            <!-- -------------------------------------------- -->
            <div class="card text-bg-primary d-none">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="d-flex flex-column h-100">
                                <div class="hstack gap-3">
                                    <span
                                        class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
                                        <iconify-icon icon="solar:course-up-outline" class="fs-7 text-muted"></iconify-icon>
                                    </span>
                                    <h5 class="text-white fs-6 mb-0 text-nowrap">Welcome Back
                                        <br>{{ session('user_name') }}
                                    </h5>
                                </div>
                                <div class="mt-4 mt-sm-auto">
                                    <div class="row">
                                        <div class="col-6">
                                            <span class="opacity-75">Wealth</span>
                                            <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                                                $100</h4>
                                        </div>
                                        <div class="col-6 border-start border-light" style="--bs-border-opacity: .15;">
                                            <span class="opacity-75">Debt</span>
                                            <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                                                $10</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-md-end">
                            <img src="https://bootstrapdemos.adminmart.com/matdash/dist/assets/images/backgrounds/welcome-bg.png"
                                alt="welcome" class="img-fluid mb-n7 mt-2" width="180">
                        </div>
                    </div>
                </div>
            </div>
            {{-- Welcome Card 2 --}}
            <style>.card:hover .card-body::before{opacity:0 !important}</style>
            <div class="card text-white bg-primary-gt overflow-hidden" style="height:225px;border-radius: 20px !important">
                <div class="card-body position-relative z-1">
                  <span class="badge badge-custom-dark d-inline-flex align-items-center gap-2 fs-3">
                    <iconify-icon icon="solar:check-circle-outline" class="fs-5"></iconify-icon>
                    <span class="fw-normal">{{ session('roles') }}</span>
                  </span>
                  <h4 class="text-white fw-normal mt-5 pt-7 mb-1">Hey, <span class="fw-bolder"><br>{{ session('user_name') }}</span>!
                  </h4>
                  <h6 class="opacity-75 fw-normal text-white mb-0 d-none"></h6>
                </div>
              </div>
            
            <div class="row">
                <!-- -------------------------------------------- -->
                <!-- Stok -->
                <!-- -------------------------------------------- -->
                <div class="col"> <!-- default col-md-6 -->
                    <div class="card bg-primary-subtle overflow-hidden shadow-none" style="height:195px;padding:0;border-radius: 20px !important">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between mb-9">
                                <div>
                                <h5 class="card-title">Stok Barang</h5>
                                  <div class="hstack gap-2">
                                    <h5 id="stok_seluruh" class="card-title fw-semibold mb-0 fs-7">0</h5>
                                    <span id="stok_surdef" class="fs-11 text-dark-light fw-semibold"></span>
                                  </div>
                                </div>
                                <span class="round-48 d-flex align-items-center justify-content-center bg-white rounded">
                                  <iconify-icon icon="solar:pie-chart-3-line-duotone" class="text-primary fs-6"></iconify-icon>
                                </span>
                              </div>
                        </div>
                        <div id="stok" style="min-height: 70px;position:relative;bottom:8px"></div>
                    </div>
                </div>
                <!-- -------------------------------------------- -->
                <!-- Projects -->
                <!-- -------------------------------------------- -->
                {{-- <div class="col-md-6">
                    <div class="card bg-danger-subtle overflow-hidden shadow-none" style="height:195px;padding:0">
                        <div class="card-body p-4">
                            <span class="text-dark-light">Projects</span>
                            <div class="hstack gap-6 mb-4">
                                <h5 class="mb-0 fs-7">78,298</h5>
                                <span class="fs-11 text-dark-light fw-semibold">+31.8%</span>
                            </div>
                            <div class="mx-n1">
                                <div id="projects" style="min-height: 46px;"></div>
                            </div>
                        </div>

                    </div>
                </div> --}}
            </div>
        </div>
        <div class="col-lg-7">
            <!-- -------------------------------------------- -->
            <!-- Overview -->
            <!-- -------------------------------------------- -->
            <div class="card" style="height:450px;border-radius: 20px !important">
                <div class="card-body pb-4">
                    <div class="d-md-flex align-items-center justify-content-between mb-4">
                        <div class="hstack align-items-center gap-3">
                            <span
                                class="d-flex align-items-center justify-content-center round-48 bg-primary-subtle rounded flex-shrink-0">
                                <iconify-icon icon="solar:layers-linear" class="fs-7 text-primary"></iconify-icon>
                            </span>
                            <div>
                                <h5 class="card-title">Overview</h5>
                                <p class="card-subtitle mb-0">Last 7 days</p>
                            </div>
                        </div>

                        <div class="hstack gap-9 mt-4 mt-md-0">
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-block flex-shrink-0 round-8 bg-primary rounded-circle"></span>
                                <span class="text-nowrap text-muted">Masuk</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-block flex-shrink-0 round-8 bg-danger rounded-circle"></span>
                                <span class="text-nowrap text-muted">Keluar</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="d-block flex-shrink-0 round-8 bg-secondary rounded-circle"></span>
                                <span class="text-nowrap text-muted">Permintaan</span>
                            </div>
                        </div>
                    </div>
                    <div style="height: 285px;" class="me-n7">
                        <div id="tiga-baris"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            {{-- Keseluruhan Stok Barang --}}
            <div class="card" style="border-radius: 20px !important">
                <div class="card-body">
                    <h4 class="card-title">Most Stock Items</h4>
                    <div id="stok-terbanyak" style="min-height: 235px"></div>
                </div>
            </div>
            <!-- -------------------------------------------- -->
            <!-- Your Performance -->
            <!-- -------------------------------------------- -->
            {{-- <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-semibold">Your Performance</h5>
                    <p class="card-subtitle mb-0 lh-base">Last check on 25 february</p>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="vstack gap-9 mt-2">
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-primary-subtle flex-shrink-0">
                                        <iconify-icon icon="solar:shop-2-linear" class="fs-7 text-primary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">64 new orders</h6>
                                        <span>Processing</span>
                                    </div>

                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                        <iconify-icon icon="solar:filters-outline" class="fs-7 text-danger"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">4 orders</h6>
                                        <span>On hold</span>
                                    </div>

                                </div>
                                <div class="hstack align-items-center gap-3">
                                    <div
                                        class="d-flex align-items-center justify-content-center round-48 rounded bg-secondary-subtle">
                                        <iconify-icon icon="solar:pills-3-linear"
                                            class="fs-7 text-secondary"></iconify-icon>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">12 orders</h6>
                                        <span>Delivered</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center mt-sm-n7">
                                <div id="your-preformance" style="min-height: 78.7px;"></div>
                                <h2 class="fs-8">275</h2>
                                <p class="mb-0">
                                    Learn insigs how to manage all aspects of your
                                    startup.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div> --}}
        </div>
        <div class="col-lg-7">
            <div class="row">
                <div class="col-md-6">
                    {{-- Permintaan --}}
                    <div class="card" style="border-radius: 20px !important;height:calc(100% - 30px)">
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Item Requests</h5>
                            {{-- <p class="card-subtitle mb-0 lh-base">Last updated just now</p> --}}
        
                            <div class="row mt-4">
                                <div class="col"> {{-- col-md-6 --}}
                                    <div class="vstack gap-9 mt-2">
                                        <div class="hstack align-items-center gap-3">
                                            <div
                                                class="d-flex align-items-center justify-content-center round-48 rounded bg-success-subtle flex-shrink-0">
                                                <iconify-icon icon="solar:recive-twice-square-linear" class="fs-7 text-success"></iconify-icon>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-nowrap">{{ $permintaan_diterima }} orders</h6>
                                                <span>Approved</span>
                                            </div>
        
                                        </div>
                                        <div class="hstack align-items-center gap-3">
                                            <div
                                                class="d-flex align-items-center justify-content-center round-48 bg-warning-subtle rounded">
                                                <iconify-icon icon="solar:filters-outline"
                                                    class="fs-7 text-warning"></iconify-icon>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $permintaan_pending }} orders</h6>
                                                <span>Pending</span>
                                            </div>
        
                                        </div>
                                        <div class="hstack align-items-center gap-3">
                                            <div
                                                class="d-flex align-items-center justify-content-center round-48 rounded bg-danger-subtle">
                                                <iconify-icon icon="solar:notification-lines-remove-broken" class="fs-7 text-danger"></iconify-icon>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $permintaan_ditolak }} orders</h6>
                                                <span>Rejected</span>
                                            </div>
        
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="text-center mt-sm-n7">
                                        <div id="your-preformance" style="min-height: 78.7px;"></div>
                                        <h2 class="fs-8">275</h2>
                                        <p class="mb-0">
                                            Learn insigs how to manage all aspects of your
                                            startup.
                                        </p>
                                    </div>
                                </div> --}}
                            </div>
        
                        </div>
                    </div>
                    <!-- -------------------------------------------- -->
                    <!-- Customers -->
                    <!-- -------------------------------------------- -->
                    {{-- <div class="card" style="border-radius: 20px !important">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <div>
                                    <h5 class="card-title fw-semibold">Customers</h5>
                                    <p class="card-subtitle mb-0">Last 7 days</p>
                                </div>
                                <span class="fs-11 text-success fw-semibold lh-lg">+26.5%</span>
                            </div>
                            <div class="py-4 my-1">
                                <div id="activity-area" style="min-height: 100px;"></div>
                            </div>
                            <div class="d-flex flex-column align-items-center gap-2 w-100 mt-3">
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <span class="d-block flex-shrink-0 round-8 bg-primary rounded-circle"></span>
                                    <h6 class="fs-3 fw-normal text-muted mb-0">April 07 - April 14</h6>
                                    <h6 class="fs-3 fw-normal mb-0 ms-auto text-muted">6,380</h6>
                                </div>
                                <div class="d-flex align-items-center gap-2 w-100">
                                    <span class="d-block flex-shrink-0 round-8 bg-light rounded-circle"></span>
                                    <h6 class="fs-3 fw-normal text-muted mb-0">Last Week</h6>
                                    <h6 class="fs-3 fw-normal mb-0 ms-auto text-muted">4,298</h6>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <div class="col-md-6">
                    <div class="card w-100" style="border-radius: 20px !important">
                        <div class="card-body">
                            <div class="mb-4">
                                <h5 class="card-title fw-semibold">Daily Activities</h5>
                            </div>
                            <div style="height: 219px; overflow-y:auto" id="activityList">
                                <ul class="timeline-widget mb-0 position-relative mb-n5">
                                    <!-- Aktivitas akan ditambahkan di sini -->
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- <div class="col-lg-8">
            <!-- -------------------------------------------- -->
            <!-- Revenue by Product -->
            <!-- -------------------------------------------- -->
            <div class="card mb-lg-0">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-3 mb-9 justify-content-between align-items-center">
                        <h5 class="card-title fw-semibold mb-0">Revenue by Product</h5>
                        <select class="form-select w-auto fw-semibold">
                            <option value="1">Sep 2024</option>
                            <option value="2">Oct 2024</option>
                            <option value="3">Nov 2024</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <ul class="nav nav-tabs theme-tab gap-3 flex-nowrap" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" data-bs-toggle="tab" href="#app" role="tab"
                                    aria-selected="true">
                                    <div class="hstack gap-2">
                                        <iconify-icon icon="solar:widget-linear" class="fs-4"></iconify-icon>
                                        <span>App</span>
                                    </div>

                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#mobile" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="hstack gap-2">
                                        <iconify-icon icon="solar:smartphone-line-duotone" class="fs-4"></iconify-icon>
                                        <span>Mobile</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#saas" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="hstack gap-2">
                                        <iconify-icon icon="solar:calculator-linear" class="fs-4"></iconify-icon>
                                        <span>SaaS</span>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" data-bs-toggle="tab" href="#other" role="tab"
                                    aria-selected="false" tabindex="-1">
                                    <div class="hstack gap-2">
                                        <iconify-icon icon="solar:folder-open-outline" class="fs-4"></iconify-icon>
                                        <span>Others</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content mb-n3">
                        <div class="tab-pane active" id="app" role="tabpanel">
                            <div class="table-responsive" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <table
                                                        class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="fw-normal ps-0">Assigned
                                                                </th>
                                                                <th scope="col" class="fw-normal">Progress</th>
                                                                <th scope="col" class="fw-normal">Priority</th>
                                                                <th scope="col" class="fw-normal">Budget</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-1.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Minecraf App</h6>
                                                                            <span>Jason Roy</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-success-subtle text-success">Low</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-2.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Web App Project</h6>
                                                                            <span>Mathew Flintoff</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning">Medium</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-3.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Modernize Dashboard</h6>
                                                                            <span>Anil Kumar</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-secondary-subtle text-secondary">Very
                                                                        High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-4.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Dashboard Co</h6>
                                                                            <span>George Cruize</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger">High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 672px; height: 356px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar"
                                        style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="mobile" role="tabpanel">
                            <div class="table-responsive" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <table
                                                        class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="fw-normal ps-0">Assigned
                                                                </th>
                                                                <th scope="col" class="fw-normal">Progress</th>
                                                                <th scope="col" class="fw-normal">Priority</th>
                                                                <th scope="col" class="fw-normal">Budget</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-2.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Web App Project</h6>
                                                                            <span>Mathew Flintoff</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning">Medium</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-3.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Modernize Dashboard</h6>
                                                                            <span>Anil Kumar</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-secondary-subtle text-secondary">Very
                                                                        High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-1.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Minecraf App</h6>
                                                                            <span>Jason Roy</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-success-subtle text-success">Low</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-4.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Dashboard Co</h6>
                                                                            <span>George Cruize</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger">High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="saas" role="tabpanel">
                            <div class="table-responsive" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <table
                                                        class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="fw-normal ps-0">Assigned
                                                                </th>
                                                                <th scope="col" class="fw-normal">Progress</th>
                                                                <th scope="col" class="fw-normal">Priority</th>
                                                                <th scope="col" class="fw-normal">Budget</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-2.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Web App Project</h6>
                                                                            <span>Mathew Flintoff</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning">Medium</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-1.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Minecraf App</h6>
                                                                            <span>Jason Roy</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-success-subtle text-success">Low</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-3.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Modernize Dashboard</h6>
                                                                            <span>Anil Kumar</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-secondary-subtle text-secondary">Very
                                                                        High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-4.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Dashboard Co</h6>
                                                                            <span>George Cruize</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger">High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="other" role="tabpanel">
                            <div class="table-responsive" data-simplebar="init">
                                <div class="simplebar-wrapper" style="margin: 0px;">
                                    <div class="simplebar-height-auto-observer-wrapper">
                                        <div class="simplebar-height-auto-observer"></div>
                                    </div>
                                    <div class="simplebar-mask">
                                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                                            <div class="simplebar-content-wrapper" tabindex="0" role="region"
                                                aria-label="scrollable content" style="height: auto; overflow: hidden;">
                                                <div class="simplebar-content" style="padding: 0px;">
                                                    <table
                                                        class="table text-nowrap align-middle table-custom mb-0 last-items-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col" class="fw-normal ps-0">Assigned
                                                                </th>
                                                                <th scope="col" class="fw-normal">Progress</th>
                                                                <th scope="col" class="fw-normal">Priority</th>
                                                                <th scope="col" class="fw-normal">Budget</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-1.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Minecraf App</h6>
                                                                            <span>Jason Roy</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-success-subtle text-success">Low</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-3.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Modernize Dashboard</h6>
                                                                            <span>Anil Kumar</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-secondary-subtle text-secondary">Very
                                                                        High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-2.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Web App Project</h6>
                                                                            <span>Mathew Flintoff</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-warning-subtle text-warning">Medium</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td class="ps-0">
                                                                    <div class="d-flex align-items-center gap-6">
                                                                        <img src="../assets/images/products/dash-prd-4.jpg"
                                                                            alt="prd1" width="48"
                                                                            class="rounded">
                                                                        <div>
                                                                            <h6 class="mb-0">Dashboard Co</h6>
                                                                            <span>George Cruize</span>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <span>73.2%</span>
                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge bg-danger-subtle text-danger">High</span>
                                                                </td>
                                                                <td>
                                                                    <span class="text-dark-light">$3.5k</span>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="simplebar-placeholder" style="width: 0px; height: 0px;"></div>
                                </div>
                                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                                </div>
                                <div class="simplebar-track simplebar-vertical" style="visibility: hidden;">
                                    <div class="simplebar-scrollbar" style="height: 0px; display: none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- -------------------------------------------- -->
            <!-- Total settlements -->
            <!-- -------------------------------------------- -->
            <div class="card bg-primary-subtle mb-0">
                <div class="card-body">
                    <div class="hstack align-items-center gap-3 mb-4">
                        <span
                            class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
                            <iconify-icon icon="solar:box-linear" class="fs-7 text-primary"></iconify-icon>
                        </span>
                        <div>
                            <p class="mb-1 text-dark-light">Total settlements</p>
                            <h4 class="mb-0 fw-bolder">$122,580</h4>
                        </div>
                    </div>
                    <div style="height: 278px;">
                        <div id="settlements" style="min-height: 315px;"></div>
                    </div>
                    <div class="row mt-4 mb-2">
                        <div class="col-md-6 text-center">
                            <p class="mb-1 text-dark-light lh-lg">Total balance</p>
                            <h4 class="mb-0 text-nowrap">$122,580</h4>
                        </div>
                        <div class="col-md-6 text-center mt-3 mt-md-0">
                            <p class="mb-1 text-dark-light lh-lg">Withdrawals</p>
                            <h4 class="mb-0">$31,640</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
    <script src="https://bootstrapdemos.adminmart.com/matdash/dist/assets/libs/simplebar/dist/simplebar.min.js"></script>

    <script src="https://bootstrapdemos.adminmart.com/matdash/dist/assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="{{ asset('assets/js/dashboard_chart.js') }}"></script>

    {{-- Total Data --}}
    <div class="col-12">
        <div class="card" style="border-radius: 20px !important">
          <div class="card-body p-4 pb-0 simplebar-mouse-entered" data-simplebar="init"><div class="simplebar-wrapper" style="margin: -24px -24px 0px;"><div class="simplebar-height-auto-observer-wrapper"><div class="simplebar-height-auto-observer"></div></div><div class="simplebar-mask"><div class="simplebar-offset" style="right: 0px; bottom: 0px;"><div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: auto; overflow: hidden;"><div class="simplebar-content" style="padding: 24px 24px 0px;">
            <div class="row flex-nowrap">
              <div class="col">
                <div class="card primary-gradient">
                  <div class="card-body text-center px-9 pb-4">
                    <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-primary flex-shrink-0 mb-3 mx-auto">
                      <iconify-icon icon="solar:box-linear" class="fs-7 text-white"></iconify-icon>
                    </div>
                    <h6 class="fw-normal fs-3 mb-1">Total Barang</h6>
                    <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ $total_barang }}</h4>
                    <a href="{{ route('barang.index') }}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                      Details</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card warning-gradient">
                  <div class="card-body text-center px-9 pb-4">
                    <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-warning flex-shrink-0 mb-3 mx-auto">
                      <iconify-icon icon="solar:inbox-in-linear" class="fs-7 text-white"></iconify-icon>
                    </div>
                    <h6 class="fw-normal fs-3 mb-1">Barang Masuk</h6>
                    <h4 id="stok_barangs" class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ $total_barang_masuk }}</h4>
                    <a href="{{ route('laporan.stok') }}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                      Details</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card secondary-gradient">
                  <div class="card-body text-center px-9 pb-4">
                    <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-secondary flex-shrink-0 mb-3 mx-auto">
                      <iconify-icon icon="solar:inbox-out-linear" class="fs-7 text-white"></iconify-icon>
                    </div>
                    <h6 class="fw-normal fs-3 mb-1">Barang Keluar</h6>
                    <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ $total_barang_keluar }}</h4>
                    <a href="{{ route('barangkeluar.index') }}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                      Details</a>
                  </div>
                </div>
              </div>
              <div class="col">
                <div class="card danger-gradient">
                  <div class="card-body text-center px-9 pb-4">
                    <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-danger flex-shrink-0 mb-3 mx-auto">
                      <iconify-icon icon="solar:notification-unread-lines-broken" class="fs-7 text-white"></iconify-icon>
                    </div>
                    <h6 class="fw-normal fs-3 mb-1">Total Permintaan</h6>
                    <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">{{ $total_permintaan }}</h4>
                    <a href="{{ route('permintaanbarangkeluar.index') }}" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                      Details</a>
                  </div>
                </div>
              </div>
              {{-- <div class="col">
                <div class="card success-gradient">
                  <div class="card-body text-center px-9 pb-4">
                    <div class="d-flex align-items-center justify-content-center round-48 rounded text-bg-success flex-shrink-0 mb-3 mx-auto">
                      <iconify-icon icon="ic:outline-forest" class="fs-7 text-white"></iconify-icon>
                    </div>
                    <h6 class="fw-normal fs-3 mb-1">Total Income</h6>
                    <h4 class="mb-3 d-flex align-items-center justify-content-center gap-1">
                      $36,715</h4>
                    <a href="javascript:void(0)" class="btn btn-white fs-2 fw-semibold text-nowrap">View
                      Details</a>
                  </div>
                </div>
              </div> --}}
            </div>
          </div></div></div></div><div class="simplebar-placeholder" style="width: 1140px; height: 278px;"></div></div><div class="simplebar-track simplebar-horizontal" style="visibility: hidden;"><div class="simplebar-scrollbar" style="width: 0px; display: none;"></div></div><div class="simplebar-track simplebar-vertical" style="visibility: hidden;"><div class="simplebar-scrollbar" style="height: 0px; display: none;"></div></div></div>
        </div>
      </div>

    <div class="row d-none" style="margin-bottom:-16px">

        {{-- <div class="card text-bg-primary" style="border-radius: 20px !important">
        <div class="card-body">
          <div class="row">
            <div class="col-sm-7">
              <div class="d-flex flex-column h-100">
                <div class="hstack gap-3">
                  <span class="d-flex align-items-center justify-content-center round-48 bg-white rounded flex-shrink-0">
                    <iconify-icon icon="solar:course-up-outline" class="fs-7 text-black"></iconify-icon>
                  </span>
                  <h5 class="text-white fs-6 mb-0 text-nowrap">Welcome Back
                    <br>Admin Gudang
                  </h5>
                </div>
                <div class="mt-4 mt-sm-auto">
                  <div class="row">
                    <div class="col-6">
                      <span class="opacity-75">Budget</span>
                      <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                        $98,450</h4>
                    </div>
                    <div class="col-6 border-start border-light" style="--bs-border-opacity: .15;">
                      <span class="opacity-75">Expense</span>
                      <h4 class="mb-0 text-white mt-1 text-nowrap fs-13 fw-bolder">
                        $2,440</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-sm-5 text-center text-md-end">
              <img src="https://bootstrapdemos.adminmart.com/matdash/dist/assets/images/backgrounds/welcome-bg.png" alt="welcome" class="img-fluid mb-n7 mt-2" width="180">
            </div>
          </div>


        </div>
      </div> --}}

        {{-- <div class="card" style="box-shadow: #cdced4 0.25rem 0.25rem 0.75rem; height: 85px;">
            <div class="card-body">
                <h4 style="color: #5a5c69; font-size: 20px;">Hallo, {{ session('user_name') }}!</h4>
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
        <div class="col d-flex align-items-strech"> {{-- col-lg-8 --}}
            <div class="card w-100" style="border-radius: 20px !important">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Incoming & Outbound Item</h5>
                            <p class="card-subtitle mb-0 lh-base">Based on the last 6 months</p>
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
                    {{-- <style>.apexcharts-bar-series.apexcharts-plot-series .apexcharts-series path{clip-path: inset(0 0 5% 0 round 20px);}</style> --}}
                    <div id="coming-out"></div>
                </div>
            </div>
        </div>

        {{-- <div class="col-lg-4 d-flex align-items-stretch">
            <div class="card w-100" style="border-radius: 20px !important">
                <!-- -------------------------------------------- -->
                    <!-- Sales Overview -->
                    <!-- -------------------------------------------- -->
                        <div class="card-body">
                            <h5 class="card-title fw-semibold">Sales Overview</h5>
                            <p class="card-subtitle mb-1">Last 7 days</p>

                            <div class="position-relative labels-chart">
                                <span class="fs-11 label-1">0%</span>
                                <span class="fs-11 label-2">25%</span>
                                <span class="fs-11 label-3">50%</span>
                                <span class="fs-11 label-4">75%</span>
                                <div id="sales-overview" style="min-height: 210.75px;"></div>
                            </div>
                        </div>
            </div>
        </div> --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Mengambil data dari API
                fetch('{{ config('app.api_url') }}/dashboard/daily-activity')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const activityList = document.querySelector('.timeline-widget');
                        activityList.innerHTML = '';

                        if (data.length === 0) {
                            const emptyMessage = document.createElement('li');
                            emptyMessage.className = 'timeline-item d-flex position-relative overflow-hidden';
                            emptyMessage.innerHTML = `
                                <div class="timeline-desc fs-3 text-dark mt-n1">
                                    No activities found today.
                                </div>
                            `;
                            activityList.appendChild(emptyMessage);
                        } else {
                            data.forEach(activity => {
                                const listItem = document.createElement('li');
                                listItem.className = 'timeline-item d-flex position-relative overflow-hidden';

                                let formattedDescription = activity.description;
                                if (activity.badge_color === "bg-success" || activity.badge_color === "bg-danger") {
                                    formattedDescription = activity.description.replace(/\n/g, '<br>');
                                }
                                const truncatedDescription = truncateText(formattedDescription, 20);
                                const fullDescription = formattedDescription;

                                listItem.innerHTML = `
                                <div class="timeline-time mt-n1 text-muted flex-shrink-0 text-end">${activity.time}</div>
                                <div class="timeline-badge-wrap d-flex flex-column align-items-center">
                                    <span class="timeline-badge ${activity.badge_color} flex-shrink-0 mt-2"></span>
                                    <span class="timeline-badge-border d-block flex-shrink-0"></span>
                                </div>
                                <div class="timeline-desc fs-3 text-dark mt-n1">
                                    <span class="truncated-text">${truncatedDescription}</span>
                                    <span class="full-text" style="display: none;">${fullDescription}</span>
                                    ${truncatedDescription !== fullDescription ? '<span class="expand-btn" style="cursor: pointer;">...</span>' : ''}
                                </div>
                            `;

                                activityList.appendChild(listItem);

                                if (truncatedDescription !== fullDescription) {
                                    const expandBtn = listItem.querySelector('.expand-btn');
                                    const truncatedText = listItem.querySelector('.truncated-text');
                                    const fullText = listItem.querySelector('.full-text');

                                    expandBtn.addEventListener('click', function() {
                                        if (truncatedText.style.display !== 'none') {
                                            truncatedText.style.display = 'none';
                                            fullText.style.display = 'inline';
                                            expandBtn.innerHTML = '<b>Show less</b>';
                                        } else {
                                            truncatedText.style.display = 'inline';
                                            fullText.style.display = 'none';
                                            expandBtn.innerHTML = '...';
                                        }
                                    });
                                }
                            });
                        }
                    })
                    .catch(error => {
                        console.error('There has been a problem with your fetch operation:', error);
                    });
            });

            function truncateText(text, maxLength) {
                if (text.length <= maxLength) return text;
                return text.substr(0, maxLength) + '';
            }
        </script>

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

    {{-- <section class="py-3 py-md-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-9 col-xl-8">
        <div class="card widget-card border-light shadow-sm">
          <div class="card-body p-4">
            <div class="d-block d-sm-flex align-items-center justify-content-between mb-3">
              <div class="mb-3 mb-sm-0">
                <h5 class="card-title widget-card-title">Sales Overview</h5>
              </div>
              <div>
                <select class="form-select text-secondary border-light-subtle">
                  <option value="1">March 2023</option>
                  <option value="2">April 2023</option>
                  <option value="3">May 2023</option>
                  <option value="4">June 2023</option>
                </select>
              </div>
            </div>
            <div id="bsb-chart-1"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  !function() {
    var a = {
        533: function() {
            !function() {
                const a = {
                    init() {
                        new ApexCharts(document.querySelector("#bsb-chart-1"), {
                            series: [{
                                name: "Sales ($)",
                                data: [
                                    { x: "Jan", y: 1965 },
                                    { x: "Feb", y: 1895 },
                                    { x: "Mar", y: 2187 },
                                    { x: "Apr", y: 2365 },
                                    { x: "May", y: 1943 },
                                    { x: "Jun", y: 2265 },
                                    { x: "Jul", y: 2489 },
                                    { x: "Aug", y: 2561 },
                                    { x: "Sep", y: 3587 },
                                    { x: "Oct", y: 3354 },
                                    { x: "Nov", y: 3865 },
                                    { x: "Dec", y: 4321 }
                                ]
                            }],
                            xaxis: {
                                type: "category"
                            },
                            chart: {
                                type: "area",
                                toolbar: {
                                    tools: {
                                        download: true,
                                        selection: false,
                                        zoom: false,
                                        zoomin: false,
                                        zoomout: false,
                                        pan: false,
                                        reset: false
                                    }
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            grid: {
                                borderColor: "transparent"
                            },
                            colors: ["var(--bs-primary)"],
                            markers: {
                                size: 0
                            },
                            fill: {
                                type: "gradient",
                                gradient: {
                                    shadeIntensity: 1,
                                    inverseColors: false,
                                    opacityFrom: 0.12,
                                    opacityTo: 0,
                                    stops: [0, 90, 100]
                                }
                            },
                            xaxis: {
                                labels: {
                                    style: {
                                        colors: Array(12).fill("#a1aab2")
                                    }
                                }
                            },
                            yaxis: {
                                labels: {
                                    style: {
                                        colors: Array(12).fill("#a1aab2")
                                    }
                                }
                            }
                        }).render();
                    }
                };

                function e() {
                    a.init();
                }

                if (document.readyState === "loading") {
                    document.addEventListener("DOMContentLoaded", e);
                } else {
                    e();
                }

                window.addEventListener("load", function() {}, false);
            }();
        }
    };

    var e = {};

    function t(o) {
        var n = e[o];
        if (n !== undefined) return n.exports;
        var r = e[o] = {
            exports: {}
        };
        return a[o](r, r.exports, t), r.exports;
    }

    t.n = function(a) {
        var e = a && a.__esModule ? function() {
            return a.default;
        } : function() {
            return a;
        };
        return t.d(e, {
            a: e
        }), e;
    };

    t.d = function(a, e) {
        for (var o in e) {
            if (t.o(e, o) && !t.o(a, o)) {
                Object.defineProperty(a, o, {
                    enumerable: true,
                    get: e[o]
                });
            }
        }
    };

    t.o = function(a, e) {
        return Object.prototype.hasOwnProperty.call(a, e);
    };

    (function() {
        "use strict";
        t(533);
    })();
}();
</script> --}}

    {{-- Item History --}}
    {{-- <div class="card" style="border-radius: 20px !important">
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
    </div> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // const btnMasuk = document.getElementById('btn-masuk');
            // const btnKeluar = document.getElementById('btn-keluar');
            // const riwayatMasuk = document.getElementById('riwayat-masuk');
            // const riwayatKeluar = document.getElementById('riwayat-keluar');

            // btnMasuk.addEventListener('click', function() {
            //     riwayatMasuk.style.display = 'block';
            //     riwayatKeluar.style.display = 'none';
            //     btnMasuk.classList.add('active');
            //     btnKeluar.classList.remove('active');
            // });

            // btnKeluar.addEventListener('click', function() {
            //     riwayatMasuk.style.display = 'none';
            //     riwayatKeluar.style.display = 'block';
            //     btnKeluar.classList.add('active');
            //     btnMasuk.classList.remove('active');
            // });

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
                        // data: countsBarangMasuk6Bulan.map(count => count === 0 ? Math.floor(Math.random() *
                        //     10) + 1 : count),
                        data: countsBarangMasuk6Bulan,
                    },
                    {
                        name: "Keluar",
                        // data: countsBarangKeluar6Bulan.map(count => count === 0 ? -Math.floor(Math
                        //     .random() * 10) - 1 : -count),
                        data: countsBarangKeluar6Bulan.map(count => -count),
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
                document.querySelector("#coming-out"),
                chart
            );
            chart.render();

            // Overview
            var chart = {
                series: [{
                        name: "Masuk",
                        data: countsBarangMasuk,
                    },
                    {
                        name: "Keluar",
                        data: countsBarangKeluar,
                    },
                    {
                        name: "Permintaan",
                        data: countsPermintaan,
                    },
                ],
                chart: {
                    toolbar: {
                        show: false,
                    },
                    type: "area",
                    fontFamily: "inherit",
                    foreColor: "#adb0bb",
                    height: 300,
                    width: "100%",
                    stacked: false,
                    offsetX: -10,
                },
                colors: ["var(--bs-primary)", "var(--bs-danger)", "var(--bs-secondary)"],
                plotOptions: {},
                dataLabels: {
                    enabled: false,
                    formatter: function(val) {
                        return val > 0 ? val : '';
                    },
                },
                legend: {
                    show: false,
                },
                stroke: {
                    width: 2,
                    curve: "monotoneCubic",
                },
                grid: {
                    show: true,
                    padding: {
                        top: 0,
                        bottom: 0,
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
                fill: {
                    type: "gradient",
                    gradient: {
                        shadeIntensity: 0,
                        inverseColors: false,
                        opacityFrom: 0.1,
                        opacityTo: 0.01,
                        stops: [0, 100],
                    },
                },
                xaxis: {
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                    categories: dates.map(date => {
                        const parsedDate = new Date(date);
                        return parsedDate.getDate();
                    }),
                },
                markers: {
                    strokeColor: [
                        "var(--bs-primary)",
                        "var(--bs-secondary)",
                        "var(--bs-danger)",
                    ],
                    strokeWidth: 2,
                },
                tooltip: {
                    theme: "dark",
                    x: {
                        formatter: function(val, opts) {
                            return dates[opts.dataPointIndex]; // Ambil tanggal dari array dates
                        }
                    },
                },
            };

            var chart = new ApexCharts(
                document.querySelector("#tiga-baris"),
                chart
            );
            chart.render();

            // Stok
            const endDate = new Date().toISOString().split('T')[0];
            const startDate = new Date(new Date().setDate(new Date().getDate() - 6)).toISOString().split('T')[0];

            // Fetch data dari API
            fetch(`{{ config('app.api_url') }}/laporan/stok?start_date=${startDate}&end_date=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    // Ambil stok keseluruhan dari API
                    const stokKeseluruhan = data.stok_keseluruhan;

                    // Extract jumlah stok per hari
                    const stokPerhari = data.stok_perhari;
                    const dates = [];
                    const stokData = [];

                    // Generate array of dates from startDate to endDate
                    let currentDate = new Date(startDate);
                    const end = new Date(endDate);
                    while (currentDate <= end) {
                        dates.push(currentDate.toISOString().split('T')[0]);
                        currentDate.setDate(currentDate.getDate() + 1);
                    }

                    // Fill stokData array with corresponding values or 0
                    dates.forEach(date => {
                        const stokEntry = stokPerhari.find(entry => entry.tanggal === date);
                        stokData.push(stokEntry ? parseInt(stokEntry.jumlah) : 0);
                    });

                    // Update stok keseluruhan di HTML
                    document.querySelector('#stok_seluruh').textContent = stokKeseluruhan.toLocaleString();

                    // Update stok barang di HTML Stok Barang
                    // document.querySelector('#stok_barang').textContent = stokKeseluruhan.toLocaleString();

                    // Hitung surplus/defisit dari stok kemarin
                    const stokYesterday = stokPerhari[stokPerhari.length - 2]?.jumlah || 0;
                    const stokToday = stokPerhari[stokPerhari.length - 1]?.jumlah || 0;
                    const stokChangePercent = stokYesterday ? (((stokToday - stokYesterday) / stokYesterday) *
                        100).toFixed(2) : 0;

                    // Update persentase di HTML
                    const surplusElement = document.querySelector('#stok_surdef');
                    if (stokChangePercent != 0) {
                        surplusElement.textContent = stokChangePercent > 0 ? `+${stokChangePercent}%` :
                            `${stokChangePercent}%`;

                        // Update warna persentase berdasarkan positif atau negatif
                        if (stokChangePercent > 0) {
                            surplusElement.classList.remove('text-danger');
                            surplusElement.classList.add('text-success');
                        } else {
                            surplusElement.classList.remove('text-success');
                            surplusElement.classList.add('text-danger');
                        }
                    } else {
                        surplusElement.textContent = '';
                        surplusElement.classList.remove('text-success', 'text-danger');
                    }

                    // Inisialisasi chart dengan data stok dari API
                    var options = {
                        chart: {
                            id: "stok",
                            type: "area",
                            height: 70,
                            sparkline: {
                                enabled: true,
                            },
                            group: "sparklines",
                            fontFamily: "inherit",
                            foreColor: "#adb0bb",
                        },
                        series: [{
                            name: "stok",
                            color: "var(--bs-primary)",
                            data: stokData,
                        }],
                        stroke: {
                            curve: "smooth",
                            width: 2,
                        },
                        fill: {
                            type: "gradient",
                            color: "var(--bs-primary)",
                            gradient: {
                                shadeIntensity: 0,
                                inverseColors: false,
                                opacityFrom: 0.2,
                                opacityTo: 0.1,
                                stops: [100],
                            },
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
                                show: true,
                                formatter: function(val, opts) {
                                    return dates[opts.dataPointIndex];
                                }
                            },
                        },
                        xaxis: {
                            categories: dates,
                        },
                    };
                    new ApexCharts(document.querySelector("#stok"), options).render();
                })
                .catch(error => {
                    console.error('Error fetching stok data:', error);
                });


            // Keseluruhan Stok Barang
            // Basic Bar Chart -------> BAR CHART
            // Fetch data from API
            fetch(`{{ config('app.api_url') }}/laporan/stok`)
                .then(response => response.json())
                .then(data => {
                    // Extracting the required data from the API response
                    const barangData = data.data;

                    // Combine quantities for items with the same barang_id
                    const combinedBarangData = barangData.reduce((acc, item) => {
                        const existingItem = acc.find(i => i.barang_id === item.barang_id);
                        if (existingItem) {
                            existingItem.jumlah = parseInt(existingItem.jumlah) + parseInt(item.jumlah);
                        } else {
                            acc.push({...item, jumlah: parseInt(item.jumlah)});
                        }
                        return acc;
                    }, []);

                    // Sort the data based on 'jumlah' in descending order
                    const sortedBarangData = combinedBarangData.sort((a, b) => b.jumlah - a.jumlah);

                    // Slice the first 6 items to display only 6 data points
                    const top6BarangData = sortedBarangData.slice(0, 6);

                    // Prepare data for the chart
                    const jumlahBarang = top6BarangData.map(item => item.jumlah); // array of 'jumlah'
                    const namaBarang = top6BarangData.map(item => item.nama_barang); // array of 'nama_barang'

                    // Basic Bar Chart with sorted data
                    var options_basic = {
                        series: [{
                            name: 'Jumlah Barang',
                            data: jumlahBarang, // Sorted data from the API
                        }],
                        chart: {
                            fontFamily: "inherit",
                            type: "bar",
                            height: 220,
                            toolbar: {
                                show: false,
                            },
                        },
                        grid: {
                            borderColor: "transparent",
                        },
                        colors: ['#635bff', '#7b6fff', '#9486ff', '#ac9dff', '#c5b4ff', '#ddcbff'],
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                distributed: true,
                            },
                        },
                        legend: {
                            show: false,
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        xaxis: {
                            categories: namaBarang,
                            labels: {
                                style: {
                                    colors: "#a1aab2",
                                },
                                formatter: function (value) {
                                    return Math.floor(value);
                                }
                            },
                            tickAmount: 6,
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: "#a1aab2",
                                },
                            },
                        },
                        tooltip: {
                            theme: "dark",
                            y: {
                                title: {
                                    formatter: function (seriesName, opts) {
                                        return opts.w.globals.labels[opts.dataPointIndex];
                                    }
                                }
                            }
                        },
                    };

                    // Render the chart
                    var chart_bar_basic = new ApexCharts(
                        document.querySelector("#stok-terbanyak"),
                        options_basic
                    );
                    chart_bar_basic.render();
                })
                .catch(error => console.error('Error fetching data:', error));

                // -----------------------------------------------------------------------
                // Customers Area
                // -----------------------------------------------------------------------
                var chart_users = {
                series: [
                    {
                    name: "April 07 ",
                    data: [0, 20, 15, 19, 14, 25, 30],
                    },
                    {
                    name: "Last Week",
                    data: [0, 8, 19, 13, 26, 16, 25],
                    },
                ],
                chart: {
                    fontFamily: "inherit",
                    height: 100,
                    type: "line",
                    toolbar: {
                    show: false,
                    },
                    sparkline: {
                    enabled: true,
                    },
                },
                colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                grid: {
                    show: false,
                },
                stroke: {
                    curve: "smooth",
                    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                    width: 2,
                },
                markers: {
                    colors: ["var(--bs-primary)", "var(--bs-primary-bg-subtle)"],
                    strokeColors: "transparent",
                },
                tooltip: {
                    theme: "dark",
                    x: {
                    show: false,
                    },
                    followCursor: true,
                },
                };
                var chart_line_basic = new ApexCharts(
                document.querySelector("#activity-area"),
                chart_users
                );
                chart_line_basic.render();


        });
    </script>


    {{-- <script>
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
    </script> --}}
@endsection
