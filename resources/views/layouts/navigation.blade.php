<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/logos/logo_ptjaringsolusi.png') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/styles.min.css') }}" />
    <!-- jQuery -->
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- Yajra Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
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
            color: #007bff !important;
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
            border-left: 0.25rem solid #007bff !important;
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

        .sidebar-sub-item a:hover span.hide-menu {
            color: #635bff !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: black
        }

        .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .icon-edit,
        .icon-delete,
        .icon-detail {
            color: #ffffff;
            font-size: 18px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 5px;
        }

        .icon-detail {
            background-color: #112337;
        }

        .icon-edit {
            background-color: #635bff;
        }

        .icon-delete {
            background-color: #c70000;
        }

        .btn-action:hover .icon-edit,
        .btn-action:hover .icon-delete {
            opacity: 0.8;
        }
    </style>

</head>

<body>
    <!--  Body Wrapper -->
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="#" class="text-nowrap logo-img">
                        <img src="{{ asset('assets/images/logos/logo_ptjaringsolusi.png') }}"
                            style="width: 100px;
                            border-radius: 7px;
                            margin-left: 50px;
                            margin-top: 15px;"
                            alt="" />
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">Home</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/dashboard" aria-expanded="false">
                                <iconify-icon icon="solar:widget-add-line-duotone"></iconify-icon>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>

                        @canany(['supplier.view', 'customer.view', 'item.view', 'item type.view', 'item status.view'])
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">MANAGEMENT</span>
                        </li>

                        @can('supplier.view')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/supplier" aria-expanded="false">
                                <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                                <span class="hide-menu">Supplier</span>
                            </a>
                        </li>
                        @endcan

                        @can('customer.view')
                        <li class="sidebar-item" style="margin-left: -1.8px;">
                            <a class="sidebar-link" href="/customer" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21.7" height="21.7"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.1"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-square-rounded">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M12 13a3 3 0 1 0 0 -6a3 3 0 0 0 0 6z" />
                                    <path d="M12 3c7.2 0 9 1.8 9 9s-1.8 9 -9 9s-9 -1.8 -9 -9s1.8 -9 9 -9z" />
                                    <path d="M6 20.05v-.05a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v.05" />
                                </svg>
                                {{-- <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon> --}}
                                <span class="hide-menu" style="margin-left: -2px;">Customer</span>
                            </a>
                        </li>
                        @endcan

                        @canany(['item.view', 'item type.view', 'item status.view'])
                        <li
                            class="sidebar-item {{ request()->is('barang') || request()->is('jenisbarang') || request()->is('statusbarang') ? 'active' : '' }}">
                            <a class="sidebar-link has-arrow {{ request()->is('barang') || request()->is('jenisbarang') || request()->is('statusbarang') ? 'active' : '' }}"
                                href="#"
                                aria-expanded="{{ request()->is('barang') || request()->is('jenisbarang') || request()->is('statusbarang') ? 'true' : 'false' }}"
                                data-bs-toggle="collapse" data-bs-target="#Submenu">
                                <iconify-icon icon="fa-solid:box-open" style="font-size: 14px;"></iconify-icon>
                                <span class="hide-menu">Item</span>
                            </a>
                            <ul id="Submenu"
                                class="collapse {{ request()->is('barang') || request()->is('jenisbarang') || request()->is('statusbarang') ? 'show' : '' }}"
                                aria-expanded="{{ request()->is('barang') || request()->is('jenisbarang') || request()->is('statusbarang') ? 'true' : 'false' }}">
                                
                                @can('item.view')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barang') ? 'active' : '' }}"
                                        href="/barang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barang') ? '#635bff' : 'gray' }};">Data</span>
                                    </a>
                                </li>
                                @endcan

                                @can('item type.view')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('jenisbarang') ? 'active' : '' }}"
                                        href="/jenisbarang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('jenisbarang') ? '#635bff' : 'gray' }};">Type</span>
                                    </a>
                                </li>
                                @endcan

                                @can('item status.view')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('statusbarang') ? 'active' : '' }}"
                                        href="/statusbarang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('statusbarang') ? '#635bff' : 'gray' }};">Status</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @can('requirement type.view')
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/keperluan" aria-expanded="false">
                                <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                <span class="hide-menu">Requirement Type</span>
                            </a>
                        </li>
                        @endcan

                        @endcanany
                        
                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>

                        @canany(['incoming item.view', 'outbound item.view'])
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear"
                                class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">TRANSACTION</span>
                        </li>
                        <li
                            class="sidebar-item {{ request()->is('barangmasuk*') || request()->is('barangkeluar') ? 'active' : '' }}">
                            <a class="sidebar-link has-arrow {{ request()->is('barangmasuk*') || request()->is('barangkeluar') ? 'active' : '' }}"
                                href="#"
                                aria-expanded="{{ request()->is('barangmasuk*') || request()->is('barangkeluar') ? 'true' : 'false' }}"
                                data-bs-toggle="collapse" data-bs-target="#itemSubmenu">
                                <iconify-icon icon="fa-solid:box-open" style="font-size: 14px;"></iconify-icon>
                                <span class="hide-menu">Item</span>
                            </a>
                            <ul id="itemSubmenu"
                                class="collapse {{ request()->is('barangmasuk*') || request()->is('barangkeluar') ? 'show' : '' }}"
                                aria-expanded="{{ request()->is('barangmasuk*') || request()->is('barangkeluar') ? 'true' : 'false' }}">

                                @can('incoming item.view')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barangmasuk*') ? 'active' : '' }}"
                                        href="/barangmasuk">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barangmasuk*') ? '#635bff' : 'gray' }};">
                                            Incoming Item
                                        </span>
                                    </a>
                                </li>
                                @endcan 

                                @can('outbound item.view')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barangkeluar') ? 'active' : '' }}"
                                        href="/barangkeluar">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barangkeluar') ? '#635bff' : 'gray' }};">
                                            Outbound Item
                                        </span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany

                        @canany(['item request.viewAll', 'item request.viewFilterbyUser'])
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('permintaanbarangkeluar/*') ? 'active' : '' }}"
                                href="/permintaanbarangkeluar" aria-expanded="false">
                                <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
                                <span class="hide-menu">Outbound Item Request</span>
                            </a>
                        </li>
                        @endcanany
                        @canany(['report.view stock', 'report.view incoming item', 'report.view outbound item'])
                        <li
                            class="sidebar-item {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'active' : '' }}">
                            <a class="sidebar-link has-arrow {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'active' : '' }}"
                                href="#" style="margin-left: -4.5px;"
                                aria-expanded="{{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'true' : 'false' }}"
                                data-bs-toggle="collapse" data-bs-target="#reportSubmenu">
                                <svg xmlns="http://www.w3.org/2000/svg" width="29" height="23"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1."
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="icon icon-tabler icons-tabler-outline icon-tabler-report">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M8 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h5.697" />
                                    <path d="M18 14v4h4" />
                                    <path d="M18 11v-4a2 2 0 0 0 -2 -2h-2" />
                                    <path
                                        d="M8 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                    <path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M8 11h4" />
                                    <path d="M8 15h3" />
                                </svg>
                                <span class="hide-menu" style="margin-left: -6px;">Report</span>
                            </a>
                            <ul id="reportSubmenu"
                                class="collapse {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'show' : '' }}"
                                aria-expanded="{{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'true' : 'false' }}">
                                @can('report.view stock')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/stok') ? 'active' : '' }}"
                                        href="/laporan/stok">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/stok') ? '#635bff' : 'gray' }};">Stock</span>
                                    </a>
                                </li>
                                @endcan
                                @can('report.view incoming item')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/barangmasuk') ? 'active' : '' }}"
                                        href="/laporan/barangmasuk">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/barangmasuk') ? '#635bff' : 'gray' }};">Incoming
                                            Item</span>
                                    </a>
                                </li>
                                @endcan
                                @can('report.view outbound item')
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/barangkeluar') ? 'active' : '' }}"
                                        href="/laporan/barangkeluar">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/barangkeluar') ? '#635bff' : 'gray' }};">Outbound
                                            Item</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany   
                        @can('roles.view')                     
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear"
                                class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">OTHER</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link {{ request()->is('roles/*') ? 'active' : '' }}"
                                href="/roles" aria-expanded="false">
                                <iconify-icon icon="fluent:person-passkey-32-regular"></iconify-icon>
                                <span class="hide-menu">Role Management</span>
                            </a>
                        </li>
                        @endcan                        
                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>
                        </li>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!--  Sidebar End -->
        <!--  Main wrapper -->
        <div class="body-wrapper">
            <!--  Header Start -->
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <a class="nav-link sidebartoggler " id="headerCollapse" href="javascript:void(0)">
                                <i class="ti ti-menu-2"></i>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <style>
                                .nav-item.dropdown:hover .dropdown-menu-end{
                                    display: block;
                                    opacity: 1;
                                    visibility: visible;
                                }

                                .dropdown-menu-end{
                                    display: none;
                                    opacity: 0;
                                    visibility: hidden;
                                    transition: all 0.2s ease;
                                    position: absolute;
                                    top: 100%;
                                    right: 0;
                                    min-width: 300px;
                                    z-index: 1000;
                                    background-color: #ffffff;
                                    border-radius: 8px;
                                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                                }
                            </style>
                            <li class="nav-item dropdown nav-icon-hover-bg rounded-circle">
                                <a class="nav-link position-relative" href="javascript:void(0)" id="drop2"
                                    aria-haspopup="true" aria-expanded="false">
                                    <iconify-icon icon="solar:bell-bing-line-duotone" class="fs-6"></iconify-icon>
                                    <div class="notification bg-primary rounded-circle"></div>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2" style="min-width: 400px">
                                    <div class="d-flex align-items-center justify-content-between py-3 px-4">
                                        <h5 class="mb-0 fs-5 fw-semibold">Notifications</h5>
                                        <span class="badge bg-primary rounded-4 px-3 py-1">2 new</span>
                                    </div>
                                    <div class="message-body" data-simplebar>
                                        <a href="javascript:void(0)"
                                            class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                            <span
                                                class="flex-shrink-0 bg-danger-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-danger">
                                                <iconify-icon icon="solar:widget-3-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Launch Dashoard</h6>
                                                    <span class="d-block fs-2">9:30 AM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11">Just see
                                                    the new dashboard!</span>
                                            </div>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="py-6 px-7 d-flex align-items-center dropdown-item gap-3">
                                            <span
                                                class="flex-shrink-0 bg-secondary-subtle rounded-circle round d-flex align-items-center justify-content-center fs-6 text-secondary">
                                                <iconify-icon icon="solar:settings-line-duotone"></iconify-icon>
                                            </span>
                                            <div class="w-75">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-1 fw-semibold">Settings</h6>
                                                    <span class="d-block fs-2">4:36 PM</span>
                                                </div>
                                                <span class="d-block text-truncate text-truncate fs-11">You can
                                                    customize this application as you want</span>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="py-3 px-4">
                                        <a href="#" class="btn btn-primary btn-sm w-100">View All
                                            Notifications</a>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('assets/images/profile/user-3.jpg') }}" alt="" width="35"
                                        height="35" class="rounded-circle">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up"
                                    aria-labelledby="drop2">
                                    <div class="message-body">
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-user fs-6"></i>
                                            <p class="mb-0 fs-3">My Profile</p>
                                        </a>
                                        {{-- <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-list-check fs-6"></i>
                                            <p class="mb-0 fs-3">My Task</p>
                                        </a> --}}
                                        <!-- Logout Button -->
                                        <a href="#" class="btn btn-outline-primary mx-3 mt-2 d-block"
                                            data-bs-toggle="modal" data-bs-target="#logoutModal">
                                            Logout
                                        </a>
                                        {{-- <a href="./authentication-login.html"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a> --}}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <!--  Header End -->
            <div class="body-wrapper-inner">
                <div class="container-fluid">
                    <!-- isi konten -->
                    @yield('content')
                </div>
                <!-- Logout Confirmation Modal -->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
                    aria-labelledby="logoutModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document" style="max-width: 400px;">
                        <div class="modal-content rounded-3 shadow">
                            <div class="modal-header border-bottom-0">
                                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <p class="mb-0">Apakah Anda yakin ingin keluar?</p>
                                    <p class="text-muted">Semua sesi yang sedang aktif akan dihentikan.</p>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-end border-top-0">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                                <button style="background-color: #c70000; color: white;" type="button"
                                    class="btn"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Start of Tawk.to Script-->
                <script type="text/javascript">
                    var Tawk_API = Tawk_API || {},
                        Tawk_LoadStart = new Date();
                    (function() {
                        var s1 = document.createElement("script"),
                            s0 = document.getElementsByTagName("script")[0];
                        s1.async = true;
                        s1.src = 'https://embed.tawk.to/671f0e014304e3196ad95191/1ib8kteba';
                        s1.charset = 'UTF-8';
                        s1.setAttribute('crossorigin', '*');
                        s0.parentNode.insertBefore(s1, s0);
                    })();
                </script>
                <!--End of Tawk.to Script-->
            </div>
        </div>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var path = window.location.pathname;

            document.querySelectorAll('#sidebarnav .sidebar-item a').forEach(function(link) {
                if (path === link.getAttribute('href') || path.startsWith(link.getAttribute('href'))) {
                    link.parentElement.classList.add('active');
                } else {
                    link.parentElement.classList.remove('active');
                }
            });
        });

        $.ajax({
            url: 'search-endpoint',
            method: 'GET',
            success: function(response) {
                // Update your content
                $('#search-results').html(response);

                // Reapply active state
                document.querySelectorAll('#sidebarnav .sidebar-item a').forEach(function(link) {
                    if (path === link.getAttribute('href') || path.startsWith(link.getAttribute(
                            'href'))) {
                        link.parentElement.classList.add('active');
                    } else {
                        link.parentElement.classList.remove('active');
                    }
                });
            }
        });
    </script>


    <script src="{{ asset('assets/js/chart.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script> --}}
    {{-- <script src="../assets/libs/simplebar/dist/simplebar.js"></script> --}}
    {{-- <script src="{{ asset('assets/js/dashboard.js') }}"></script> --}}
    <script src="{{ asset('assets/js/iconify-icon.min.js') }}"></script>
</body>

</html>
