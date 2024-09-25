@extends('layouts.navigation')

@section('content')

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- FontAwesome CDN -->
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

    th, td {
        padding: 8px;
        text-align: left;
        color: black;
    }

    th {
        background-color: #f2f2f2;
        cursor: default;
        font-weight: bold;
        color: rgba(0, 0, 0, 0.829);
    }

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

    .btn-actions-container {
        display: flex; /* Align items in a row */
        justify-content: flex-end; /* Align items to the right */
        gap: 5px; /* Space between buttons */
        align-items: center; /* Center items vertically */
    }

    .btn-action {
        border: none; /* Remove border */
        background: none; /* Remove background */
        padding: 0; /* Remove padding */
        cursor: pointer; /* Pointer cursor for interaction */
    }

    .icon-edit, .icon-delete, .icon-detail, .icon-tambah {
        color: #ffffff; 
        font-size: 18px;
        width: 30px; 
        height: 30px; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        margin-right: 5px;
    }

    .icon-detail {
        background-color: #112337;
    }

    .icon-tambah {
        background-color: #01578d;
    }

    .icon-edit {
        background-color: #01578d;
    }

    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
    }

    /* Ensure the button and controls container adapt well to various screen sizes */
    .controls-container {
    display: flex;
    align-items: center; /* Align items vertically in the center */
    gap: 10px; /* Spacing between elements */
    flex-wrap: wrap; /* Allow items to wrap to the next line on smaller screens */
    }

    .controls-container label {
    margin-right: 5px; /* Space between label and select */
    }

    .controls-container select {
    margin-right: 5px; /* Space between select and other elements */
    }

    .btn-primary {
    display: flex; /* Align icon and text in a row */
    align-items: center;
    background-color: #635bff;
    color: #ffffff;
    border: none;
    padding: 7px 10px;
    border-radius: 10px;
    cursor: pointer;
    font-size: 14px; /* Adjust font size for better responsiveness */
    white-space: nowrap; /* Prevent text from wrapping */
    }

    .btn-primary:hover {
    background-color: #0056b3; /* Color on hover */
    }

    /* Media query for small screens */
    @media (max-width: 576px) {
    .search-container {
        flex-direction: column; /* Stack items vertically */
        align-items: flex-start; /* Align items to the start of the container */
    }

    .search-box input[type="search"] {
        width: 100%; /* Make the search input full width */
        margin-bottom: 10px; /* Add space below the search input */
    }

    .btn-primary {
        width: 100%; /* Make the button full width */
        text-align: center; /* Center the text */
        font-size: 16px; /* Adjust font size for better readability */
    }

    .controls-container {
        flex-direction: column; /* Stack controls vertically on small screens */
        align-items: stretch; /* Stretch controls to full width */
    }
    }

    /* Flex container for info and pagination */
    .info-pagination-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .info-text {
        margin: 0;
        font-size: 14px;
        color: #8a8a8a;
    }

    .pagination-container {
        margin: 0;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 20px;
        text-align: right;
    }

    .pagination {
        display: inline-flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
    }

    .pagination .page-item {
        display: inline;
    }

    .pagination .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin: 0;
        font-size: 14px; /* Ukuran font untuk ikon */
        line-height: 1.25;
        color: #007bff;
        text-decoration: none;
        background-color: #ffffff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }

    .pagination .page-link .fa {
        font-size: 14px; /* Ukuran font ikon panah */
    }

    .pagination .page-item.active .page-link {
        background-color: #635bff;
        border-color: #635bff;
        color: #ffffff;
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
    }

    .pagination .page-item .page-link:hover {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    .hidden {
        display: none;
    }

    /* Modal Body */
    .modal-body {
        padding: 1.5rem;
        color: black;
    }

    /* Detail Item */
    .detail-item {
        margin-bottom: 1rem; /* Jarak antara baris detail */
        display: flex;
        align-items: center; /* Vertically center the content */
    }

    /* Label */
    .detail-item strong {
        margin-right: 1rem; /* Jarak antara label dan isi */
        flex: 0 0 100px; /* Width of label column */
    }

    /* Isi Detail */
    .detail-item span {
        color: #333; 
        font-size: 1rem; 
    }
</style>

<div class="container mt-3" style="padding: 30px; padding-bottom: 13px;">
    <h4 style="color: #8a8a8a;">Incoming Item</h4>
        <div class="d-flex justify-content-end" style="padding-top: 0px; margin-right: -15px;">
            <!-- Add Button -->
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center me-2"
                data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px; margin-bottom: 50px;">
                <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                Add
            </a>
            <!-- Delete Selected Button -->
            <button id="deleteSelected" class="btn btn-danger d-none"
                style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                Delete Selected
            </button>
        </div>

    <div class="table-responsive">
        <table id="barangMasukTable" class="table">
            <thead class="thead-lightblue">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>No</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tanggal Masuk</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<!-- Include the required JS files -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#barangMasukTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ config('app.api_url') }}/barangmasuk", // Route to fetch data
                type: 'GET',
                data: function(d) {
                    d.search = $('#search-input').val(); // Include search input in the AJAX request
                }
            },
            columns: [
                { data: 'select', orderable: false, searchable: false },
                { data: 'DT_RowIndex', name: 'DT_RowIndex' },
                { data: 'nama_barang', name: 'nama_barang' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'keterangan', name: 'keterangan' },
                { data: 'tanggal', name: 'tanggal' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });

        // Search functionality
        $('#search-input').on('keyup change', function() {
            table.draw();
        });

        // Handle select all checkboxes
        $('#select-all').on('click', function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            toggleDeleteButton();
        });

        // Handle checkbox change event
        $('#barangMasukTable tbody').on('change', 'input[type="checkbox"]', function() {
            toggleDeleteButton();
        });

        // Toggle delete button visibility
        function toggleDeleteButton() {
            var anyChecked = $('#barangMasukTable tbody input[type="checkbox"]:checked').length > 0;
            $('#delete-selected').toggleClass('hidden', !anyChecked);
        }

        // Add event listener for delete selected
        $('#delete-selected').on('click', function() {
            // Your delete logic here
            alert('Delete selected items');
        });
    });
</script>

@endsection