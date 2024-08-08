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

    .icon-edit, .icon-delete, .icon-detail {
        color: #ffffff; 
        font-size: 18px;
        width: 30px; 
        height: 30px; 
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50px;
        margin-right: 0px;
    }

    .icon-detail {
        background-color: #112337;
    }

    .icon-edit {
        background-color: #01578d;
    }

    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete, .btn-action:hover .icon-detail {
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
    <h4 class="mb-4" style="color: #8a8a8a;">Supplier Management</h4>
    <div class="search-container">
        <form action="{{ route('supplier.index') }}" method="GET" class="search-box">
            <input type="search" id="search-input" name="search" placeholder="Search..." value="{{ request('search') }}">
            <iconify-icon id="search-icon" name="search" icon="carbon:search" class="search-icon"></iconify-icon>
        </form>
        <div class="controls-container">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                Add
            </button> 
            <button id="delete-selected"
                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-2xl text-sm py-2 px-3 text-center hidden"
                style="background-color: #910a0a;
                        border-radius: 10px;
                        height: 35px;
                        border: none;">
                <i icon="mdi:delete" class="fas fa-trash-alt" style="margin-right: 5px; font-size: 16px;"></i>
                Hapus Terpilih
            </button>
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

    <div class="table-responsive">
        @if (!$data->isEmpty())
        <table class="table">
            <thead class="thead-lightblue">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <input type="checkbox" id="select-all">
                    </th>
                    <th>No</th>
                    <th>Supplier</th>
                    <th>Address</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $d)
                <tr>
                    <td class="w-4 px-6 py-4">
                        <input type="checkbox" class="select-item flex justify-center items-center"
                            value="{{ $d->id }}">
                    </td>
                    <td>{{ $loop->iteration + ($data->currentPage() - 1) * $data->perPage() }}</td>
                    <td>{{ $d->nama }}</td>
                    <td>{{ $d->alamat }}</td>
                    <td>
                        <button type="button" aria-label="Detail" data-id="{{ $d->id }}" class="btn-detail btn-action" style="border: none;">
                            <iconify-icon icon="mdi:file-document-outline" class="icon-detail"></iconify-icon>
                        </button>
                        <button type="button" class="btn-edit btn-action" 
                            data-id="{{ $d->id }}" 
                            data-nama="{{ $d->nama }}"
                            data-alamat="{{ $d->alamat }}" 
                            data-telepon="{{ $d->telepon }}"
                            data-keterangan="{{ $d->keterangan }}"
                            data-bs-toggle="modal" data-bs-target="#editData"
                            aria-label="Edit">
                            <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                        </button>
                        <button type="button" data-id="{{ $d->id }}" class="btn-action" aria-label="Hapus"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
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

<!-- Modal Detail Data -->
<div class="modal fade" id="detailData" tabindex="-1" aria-labelledby="detailDataLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailDataLabel">Detail Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="detail-item">
                    <strong>Supplier:</strong> <span id="detail-nama"></span>
                </div>
                <div class="detail-item">
                    <strong>Address:</strong> <span id="detail-alamat"></span>
                </div>
                <div class="detail-item">
                    <strong>Phone:</strong> <span id="detail-telepon"></span>
                </div>
                <div class="detail-item">
                    <strong>Description:</strong> <span id="detail-keterangan"></span>
                </div>
            </div>
        </div>
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
                Apakah Anda yakin ingin menghapus <span id="itemName"></span> ini?
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
                <h5 class="modal-title" id="tambahDataModalLabel">Tambah Data Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('supplier.store') }}" enctype="multipart/form-data">
                    @csrf
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
                        <label for="nama" class="form-label">Supplier</label>
                        <input type="text" id="nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Address</label>
                        <input type="text" id="alamat" name="alamat" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Phone</label>
                        <input type="text" id="telepon" name="telepon" class="form-control">
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
                <h5 class="modal-title" id="editDataLabel">Edit Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Supplier</label>
                        <input type="text" id="edit-nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-alamat" class="form-label">Address</label>
                        <input type="text" id="edit-alamat" name="alamat" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-telepon" class="form-label">Phone</label>
                        <input type="text" id="edit-telepon" name="telepon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="edit-keterangan" class="form-label">Description</label>
                        <input type="text" id="edit-keterangan" name="keterangan" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>                
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editModal = new bootstrap.Modal(document.getElementById('editData'));

    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const supplierId = this.getAttribute('data-id');
            const supplierNama = this.getAttribute('data-nama');
            const supplierAlamat = this.getAttribute('data-alamat');
            const supplierTelp = this.getAttribute('data-telepon');
            const supplierKet = this.getAttribute('data-keterangan');

            // Update form action URL with the correct id
            document.getElementById('editForm').action = `/supplier/update/${supplierId}`;

            // Populate form fields
            document.getElementById('edit-nama').value = supplierNama;
            document.getElementById('edit-alamat').value = supplierAlamat;
            document.getElementById('edit-telepon').value = supplierTelp;
            document.getElementById('edit-keterangan').value = supplierKet;

            // Show the modal
            editModal.show();
        });
    });
});
</script>

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
                const itemId = this.getAttribute('data-id');
                const itemName = this.closest('tr').querySelector('td:nth-child(3)').innerText;
                
                document.getElementById('itemName').innerText = itemName;
                document.getElementById('deleteForm').action = `/supplier/delete/${itemId}`;
                
                deleteModal.show();
            });
        });
    });
    </script>

{{-- untuk hapus terpilih --}}
<script>
    document.getElementById('select-all').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('.select-item');
        checkboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
        toggleDeleteButton();
    });

    document.querySelectorAll('.select-item').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            toggleDeleteButton();
        });
    });

    function toggleDeleteButton() {
        const selected = document.querySelectorAll('.select-item:checked').length;
        const deleteButton = document.getElementById('delete-selected');
        if (selected > 0) {
            deleteButton.classList.remove('hidden');
        } else {
            deleteButton.classList.add('hidden');
        }
    }

    document.getElementById('delete-selected').addEventListener('click', function() {
        const selected = [];
        document.querySelectorAll('.select-item:checked').forEach(checkbox => {
            selected.push(checkbox.value);
        });

        if (selected.length > 0) {
            if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                fetch('/supplier/delete-selected', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: selected
                    })
                }).then(response => {
                    if (response.ok) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus data.');
                    }
                });
            }
        } else {
            alert('Tidak ada data yang dipilih.');
        }
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

{{-- detail --}}
<script>
    $(document).ready(function() {
        // When the button to open the modal is clicked
        $('.btn-detail').on('click', function() {
            var supplierId = $(this).data('id'); // Get ID from button
    
            $.ajax({
                url: '/supplier/detail/' + supplierId,
                method: 'GET',
                success: function(data) {
                    // Fill data into the modal
                    $('#detail-nama').text(data.nama);
                    $('#detail-alamat').text(data.alamat);
                    $('#detail-telepon').text(data.telepon);
                    $('#detail-keterangan').text(data.keterangan);
    
                    // Show the modal
                    $('#detailData').modal('show');
                },
                error: function() {
                    alert('Error fetching data.');
                }
            });
        });
    });
</script>
    
@endsection