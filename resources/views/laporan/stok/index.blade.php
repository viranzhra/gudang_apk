@extends('layouts.navigation')

@section('content')

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
        <link rel="stylesheet" href="../assets/css/styles.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- FontAwesome CDN -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css">
    </head>

    <style>
        .card {
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .table-responsive {
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            color: black;
        }

        th {
            background-color: #f2f2f2;
            cursor: default;
            font-weight: semibold;
            color: rgba(0, 0, 0, 0.829);
        }

        /* search */
        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .search-box {
            display: flex;
            align-items: center;
            position: relative;
        }

        .search-box input[type="search"] {
            padding: 5px 30px 5px 10px;
            width: 250px;
            height: 40px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-box .search-icon {
            position: absolute;
            right: 5px;
            padding: 5px;
            font-size: 18px;
            border-radius: 4px;
            color: gray;
            cursor: pointer;
        }

        .search-container label {
            margin-right: 10px;
        }

        .search-container select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-action {
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            margin-right: 5px;
        }

        .icon-edit {
            color: #ffffff;
            background-color: #12b75c;
            font-size: 20px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .icon-delete {
            color: #ffffff;
            background-color: #dc3545;
            font-size: 20px;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }

        .btn-action:hover .icon-edit,
        .btn-action:hover .icon-delete {
            opacity: 0.8;
        }

        .controls-container {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .controls-container label {
            margin-right: 5px;
        }

        .controls-container select {
            margin-right: 5px;
        }

        .btn-primary {
            display: flex;
            align-items: center;
            background-color: #635bff;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            white-space: nowrap;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            /* Color on hover */
        }

        /* Media query for small screens */
        @media (max-width: 576px) {
            .search-container {
                flex-direction: column;
                /* Stack items vertically */
                align-items: flex-start;
                /* Align items to the start of the container */
            }

            .search-box input[type="search"] {
                width: 100%;
                /* Make the search input full width */
                margin-bottom: 10px;
                /* Add space below the search input */
            }

            .btn-primary {
                width: 100%;
                /* Make the button full width */
                text-align: center;
                /* Center the text */
                font-size: 16px;
                /* Adjust font size for better readability */
            }

            .controls-container {
                flex-direction: column;
                /* Stack controls vertically on small screens */
                align-items: stretch;
                /* Stretch controls to full width */
            }
        }
    </style>

    <div class="container mt-3" style="padding: 30px;">
        <h4 class="mb-4" style="color: #8a8a8a;">Stock Report</h4>
        <div class="search-container">
            <form action="" method="GET" class="search-box">
                <input type="search" id="search-input" name="search" placeholder="Search..."
                    value="{{ request('search') }}">
                <iconify-icon id="search-icon" name="search" icon="carbon:search" class="search-icon"></iconify-icon>
            </form>
        </div>

        <div class="table-responsive">
            @if (!$data->isEmpty())
            <table class="table">
                <thead class="thead-lightblue">
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Type</th>
                        <th>Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                            <td>{{ $d->nama_barang }}</td>
                            <td>{{ $d->nama_jenis_barang }}</td>
                            <td>{{ $d->jumlah ?: 0 }}</td>
                            <td>
                                <button type="button" class="btn btn-action"
                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $d->barang_id }}">
                                    <!-- SVG -->
                                    <svg class=line fill=none height=18 stroke=white stroke-width=2
                                        viewBox="0 0 24 24"width=18 xmlns=http://www.w3.org/2000/svg>
                                        <g transform="translate(3.649800, 2.749900)">
                                            <line x1=10.6555 x2=5.2555 y1=12.6999 y2=12.6999></line>
                                            <line x1=8.6106 x2=5.2546 y1=8.6886 y2=8.6886></line>
                                            <path
                                                d="M16.51,5.55 L10.84,0.15 C10.11,0.05 9.29,0 8.39,0 C2.1,0 -1.95399252e-14,2.32 -1.95399252e-14,9.25 C-1.95399252e-14,16.19 2.1,18.5 8.39,18.5 C14.69,18.5 16.79,16.19 16.79,9.25 C16.79,7.83 16.7,6.6 16.51,5.55 Z">
                                            </path>
                                            <path
                                                d="M10.2844,0.0827 L10.2844,2.7437 C10.2844,4.6017 11.7904,6.1067 13.6484,6.1067 L16.5994,6.1067">
                                            </path>
                                        </g>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="info-pagination-container">
                <div class="info-text">
                    Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data pada
                    halaman {{ $data->currentPage() }}.
                </div>
                <div class="pagination-container">
                    {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @else
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
                <div class="mx-auto max-w-screen-sm text-center">
                    <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Data tidak
                        ditemukan.</p>
                </div>
            </div>
            @endif
        </div>
    </div>
@endsection
