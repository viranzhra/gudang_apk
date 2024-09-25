<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Matdash Free</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Yajra Datatables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

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
                        <img src="../assets/images/logos/logo_ptjaringsolusi.png" style="width: 100px;
                            border-radius: 7px;
                            margin-left: 50px;
                            margin-top: 15px;" alt="" />
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
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">MANAGEMENT</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/supplier" aria-expanded="false">
                                <iconify-icon icon="solar:bookmark-square-minimalistic-line-duotone"></iconify-icon>
                                <span class="hide-menu">Supplier</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="/customer" aria-expanded="false">
                                <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                <span class="hide-menu">Customer</span>
                            </a>
                        </li>
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
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barang') ? 'active' : '' }}"
                                        href="/barang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barang') ? '#635bff' : 'gray' }};">Data</span>
                                    </a>
                                </li>
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('jenisbarang') ? 'active' : '' }}"
                                        href="/jenisbarang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('jenisbarang') ? '#635bff' : 'gray' }};">Type</span>
                                    </a>
                                </li>
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('statusbarang') ? 'active' : '' }}"
                                        href="/statusbarang">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('statusbarang') ? '#635bff' : 'gray' }};">Status</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#" aria-expanded="false">
                                <iconify-icon icon="solar:layers-minimalistic-bold-duotone"></iconify-icon>
                                <span class="hide-menu">Type Requirement</span>
                            </a>
                        </li>
                        <li>
                            <span class="sidebar-divider lg"></span>
                        </li>
                        <li class="nav-small-cap">
                            <iconify-icon icon="solar:menu-dots-linear" class="nav-small-cap-icon fs-4"></iconify-icon>
                            <span class="hide-menu">TRANSACTION</span>
                        </li>
                        <li
                            class="sidebar-item {{ request()->is('barangmasuk') || request()->is('barangkeluar') ? 'active' : '' }}">
                            <a class="sidebar-link has-arrow {{ request()->is('barangmasuk') || request()->is('barangkeluar') ? 'active' : '' }}"
                                href="#"
                                aria-expanded="{{ request()->is('barangmasuk') || request()->is('barangkeluar') ? 'true' : 'false' }}"
                                data-bs-toggle="collapse" data-bs-target="#itemSubmenu">
                                <iconify-icon icon="fa-solid:box-open" style="font-size: 14px;"></iconify-icon>
                                <span class="hide-menu">Item</span>
                            </a>
                            <ul id="itemSubmenu"
                                class="collapse {{ request()->is('barangmasuk') || request()->is('barangkeluar') ? 'show' : '' }}"
                                aria-expanded="{{ request()->is('barangmasuk') || request()->is('barangkeluar') ? 'true' : 'false' }}">
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barangmasuk') ? 'active' : '' }}"
                                        href="/barangmasuk">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barangmasuk') ? '#635bff' : 'gray' }};">Incoming
                                            Item</span>
                                    </a>
                                </li>
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('barangkeluar') ? 'active' : '' }}"
                                        href="/barangkeluar">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('barangkeluar') ? '#635bff' : 'gray' }};">Outbound
                                            Item</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#" aria-expanded="false">
                                <iconify-icon icon="solar:file-text-line-duotone"></iconify-icon>
                                <span class="hide-menu">Outbound Item Request</span>
                            </a>
                        </li>
                        <li
                            class="sidebar-item {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'active' : '' }}">
                            <a class="sidebar-link has-arrow {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'active' : '' }}"
                                href="#"
                                aria-expanded="{{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'true' : 'false' }}"
                                data-bs-toggle="collapse" data-bs-target="#reportSubmenu">
                                <iconify-icon icon="fa-solid:box-open" style="font-size: 14px;"></iconify-icon>
                                <span class="hide-menu">Report</span>
                            </a>
                            <ul id="reportSubmenu"
                                class="collapse {{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'show' : '' }}"
                                aria-expanded="{{ request()->is('laporan/stok') || request()->is('laporan/barangmasuk') || request()->is('laporan/barangkeluar') ? 'true' : 'false' }}">
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/stok') ? 'active' : '' }}"
                                        href="/laporan/stok">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/stok') ? '#635bff' : 'gray' }};">Stock</span>
                                    </a>
                                </li>
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/barangmasuk') ? 'active' : '' }}"
                                        href="/laporan/barangmasuk">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/barangmasuk') ? '#635bff' : 'gray' }};">Incoming
                                            Item</span>
                                    </a>
                                </li>
                                <li class="sidebar-sub-item">
                                    <a class="sidebar-link {{ request()->is('laporan/barangkeluar') ? 'active' : '' }}"
                                        href="/laporan/barangkeluar">
                                        <span class="hide-menu"
                                            style="color: {{ request()->is('laporan/barangkeluar') ? '#635bff' : 'gray' }};">Outbound
                                            Item</span>
                                    </a>
                                </li>
                            </ul>
                        </li>
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
                        <li class="nav-item">
                            <a class="nav-link " href="javascript:void(0)">
                                <iconify-icon icon="solar:bell-linear" class="fs-6"></iconify-icon>
                                <div class="notification bg-primary rounded-circle"></div>
                            </a>
                        </li>
                    </ul>
                    <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
                        <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">
                            <li class="nav-item dropdown">
                                <a class="nav-link " href="javascript:void(0)" id="drop2"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="../assets/images/profile/user-1.jpg" alt="" width="35"
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
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-mail fs-6"></i>
                                            <p class="mb-0 fs-3">My Account</p>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="d-flex align-items-center gap-2 dropdown-item">
                                            <i class="ti ti-list-check fs-6"></i>
                                            <p class="mb-0 fs-3">My Task</p>
                                        </a>
                                        <a href="./authentication-login.html"
                                            class="btn btn-outline-primary mx-3 mt-2 d-block">Logout</a>
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


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/apexcharts/dist/apexcharts.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>
