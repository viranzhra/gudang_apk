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
        <h4 class="mb-4" style="color: #8a8a8a;">Outbound Item Report</h4>
        <div class="search-container">
            <form action="" method="GET" class="search-box">
                <input type="search" id="search-input" name="search" placeholder="Search..."
                    value="{{ request('search') }}">
                <iconify-icon id="search-icon" name="search" icon="carbon:search" class="search-icon"></iconify-icon>
            </form>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="date" class="form-control" id="datepicker" placeholder="From" />
                        <span class="input-group-text bg-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="input-group">
                        <input type="text" class="form-control" id="datepicker2" placeholder="To" />
                        <span class="input-group-text bg-white">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            @if (!$data->isEmpty())
            <table class="table">
                <thead class="thead-lightblue">
                    <tr>
                        <th>No</th>
                        <th>Recipients</th>
                        <th>Purposes</th>
                        <th>Quantity</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $d)
                        <tr>
                            <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                            <td>{{ $d->nama_customer }}</td>
                            <td>{{ $d->nama_keperluan }}</td>
                            <td>{{ $d->jumlah }}</td>
                            <td>{{ $d->tanggal }}</td>
                            <td>
                                
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
