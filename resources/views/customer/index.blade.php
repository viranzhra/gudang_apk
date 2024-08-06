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

    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
        margin-right: 5px;
    }

    .icon-edit, .icon-delete {
        color: #ffffff; 
        font-size: 18px;
        width: 30px; 
        height: 30px; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px; 
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
    padding: 5px 10px;
    border-radius: 4px;
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
</style>

<div class="container mt-3" style="padding: 30px; padding-bottom: 13px;">
    <h4 class="mb-4" style="color: #8a8a8a;">Customer Management</h4>
        <div class="search-container">
            <form action="{{ route('customer.index') }}" method="GET" class="search-box">
                <input type="search" id="search-input" name="search" placeholder="Search..." value="{{ request('search') }}">
                <iconify-icon id="search-icon" name="search" icon="carbon:search" class="search-icon"></iconify-icon>
            </form>
            <div class="controls-container">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                    <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                    Add
                </a>                    
            </div>
        </div>

    <!-- Notifikasi flash message -->
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="table-responsive">
        @if (!$data->isEmpty())
        <table class="table">
            <thead class="thead-lightblue">
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->alamat }}</td>
                    <td>{{ $d->telepon }}</td>
                    <td>{{ $d->keterangan }}</td>
                    <td>
                        <button type="button" class="btn-edit btn-action" 
                            data-id="{{ $d->id }}" 
                            data-nama="{{ $d->nama }}"
                            data-alamat="{{ $d->alamat }}" 
                            data-telepon="{{ $d->telepon }}" 
                            data-keterangan="{{ $d->keterangan }}" 
                            aria-label="Edit">
                            <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                        </button>
                        <button data-id="{{ $d->id }}" class="btn-action" aria-label="Hapus">
                            <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="info-pagination-container">
            <div class="info-text">
                Menampilkan {{ $data->firstItem() }} - {{ $data->lastItem() }} dari {{ $data->total() }} data pada halaman {{ $data->currentPage() }}.
            </div>
            <div class="pagination-container">
                {{ $data->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @else
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
            <div class="mx-auto max-w-screen-sm text-center">
                <p class="mb-4 text-3xl tracking-tight font-bold text-gray-900 md:text-4xl dark:text-white">Data tidak ditemukan.</p>
            </div>   
        </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                Apakah Anda yakin ingin menghapus <span id="customerName"></span> ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>                               
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahDataModalLabel">Add Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('customer.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Name</label>
                        <input type="text" id="nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Phone</label>
                        <input type="text" inputmode="numeric" id="telepon" name="telepon" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Description</label>
                        <input type="text" id="keterangan" name="keterangan" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Data -->
<div class="modal fade" id="editData" tabindex="-1" aria-labelledby="editDataLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDataLabel">Edit Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Ups!</strong> Terjadi kesalahan:
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label for="nama" class="form-label">Name</label>
                        <input type="text" id="edit-nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" id="edit-alamat" name="alamat" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Phone</label>
                        <input type="text" id="edit-telepon" name="telepon" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Description</label>
                        <input type="text" id="edit-keterangan" name="keterangan" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Edit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = new bootstrap.Modal(document.getElementById('editData'));

        document.querySelectorAll('.btn-edit').forEach(button => {
            button.addEventListener('click', function() {
                const customerId = this.getAttribute('data-id');
                const customerNama = this.getAttribute('data-nama');
                const customerAlamat = this.getAttribute('data-alamat');
                const customerTelepon = this.getAttribute('data-telepon');
                const customerKeterangan = this.getAttribute('data-keterangan');

                // Update form action URL with the correct id
                document.getElementById('editForm').action = `/customer/update/${customerId}`;

                // Populate form fields
                document.getElementById('edit-nama').value = customerNama;
                document.getElementById('edit-alamat').value = customerAlamat;
                document.getElementById('edit-telepon').value = customerTelepon;
                document.getElementById('edit-keterangan').value = customerKeterangan;

                // Show the modal
                editModal.show();
            });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const searchIcon = document.getElementById('search-icon');
    const searchForm = searchIcon.closest('form');

    searchIcon.addEventListener('click', function() {
        searchForm.submit(); // Submit the form
    });
});

</script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to auto-hide alerts
        function autoHideAlert(selector, timeout) {
            const alerts = document.querySelectorAll(selector);
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.classList.remove('show');
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 500); // Remove alert after fade transition
                }, timeout);
            });
        }

        // Hide success messages after 3 seconds
        autoHideAlert('.alert-success', 3000);

        // Hide error messages after 3 seconds
        autoHideAlert('.alert-danger', 3000);
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));

    document.querySelectorAll('[aria-label="Hapus"]').forEach(button => {
        button.addEventListener('click', function() {
            const customerId = this.getAttribute('data-id');
            const customerName = this.closest('tr').querySelector('td:nth-child(2)').innerText;
            
            document.getElementById('customerName').innerText = customerName;
            document.getElementById('deleteForm').action = `/customer/delete/${customerId}`;
            
            deleteModal.show();
        });
    });
});
</script>


@endsection
