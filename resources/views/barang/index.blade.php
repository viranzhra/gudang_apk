@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-delete, .icon-detail {
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
        background-color: #01578d;
    }

    .icon-delete {
        background-color: #910a0a;
    }

    .btn-action:hover .icon-edit, .btn-action:hover .icon-delete {
        opacity: 0.8; 
    }
</style>

<div class="container mt-3" style="padding: 40px; padding-bottom: 15px; padding-top: 10px; width: 1160px;">
    <h4 class="mt-3" style="color: #8a8a8a;">Item Status</h4>
    <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
        <!-- Add Button -->
        <button type="button" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
            <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
            Add
        </button>              
    
        <!-- Delete Selected Button -->
        <button id="deleteSelected" class="btn btn-danger d-none" style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
            <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
            Delete Selected
        </button>          
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
    
    <table id="barangtable" class="table table-bordered table-striped table-hover" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 20px"><input type="checkbox" id="select-all"></th>
                <th>No</th>
                <th>Item Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<!-- Modal Tambah Data -->
<div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahDataModalLabel">Add Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('barang.store') }}" enctype="multipart/form-data">
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
                        <label for="nama" class="form-label">Item Type</label>
                        <input type="text" id="nama" name="nama" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>                
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
                Apakah Anda yakin ingin menghapus status barang <span id="itemName"></span> ini?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
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
                <h5 class="modal-title" id="editDataLabel">Edit Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                
                    <div class="mb-3">
                        <label for="edit-nama" class="form-label">Item Type</label>
                        <input type="text" id="edit-nama" name="nama" class="form-control" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>                
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<!-- DataTables Bootstrap 4 integration -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).on('click', '.btn-edit', function() {
    const itemId = $(this).data('id');  // Get the ID of the item
    const itemName = $(this).data('nama');  // Get the item name

    // Update the form action URL with the correct item ID
    $('#editForm').attr('action', `/barang/update/${itemId}`);

    // Populate form fields with the item data
    $('#edit-nama').val(itemName);

    // Show the modal
    $('#editData').modal('show');
});

document.addEventListener('DOMContentLoaded', function() {
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

    autoHideAlert('.alert-success', 3000);
    autoHideAlert('.alert-danger', 3000);
});

$('#deleteModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    const itemId = button.data('id'); // Extract info from data-* attributes
    const itemName = button.closest('tr').find('td:nth-child(3)').text();

    $('#itemName').text(itemName);
    $('#deleteForm').attr('action', `/barang/delete/${itemId}`);
});
</script>
<script>
$(document).ready(function() {
    $('#barangtable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ config('app.api_url') }}/barang',
            headers: {
                'Authorization': 'Bearer ' + '{{ session('token') }}'
            },
            error: function (xhr, error, thrown) {
                console.log('Error fetching data: ', thrown);
            }
        },
        columns: [
            { data: 'id', orderable: false, render: function(data) {
                return `<input type="checkbox" class="select-item" value="${data}">`;
            }},
            { data: null, sortable: false, render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { data: 'nama_barang', name: 'barang.nama' },
            { data: 'nama_jenis_barang', name: 'jenis_barang.nama' },
            { data: 'nama_supplier', name: 'supplier.nama' },
            { data: 'jumlah', name: 'barang_masuk.jumlah', searchable: false, orderable: false },
            { data: 'keterangan_barang', name: 'barang.keterangan' },
            { data: 'id', orderable: false, render: function(data, type, row) {
                return `
                    <button type="button" class="btn-edit btn-action" 
                        data-id="${row.id}" 
                        data-nama="${row.nama_barang}"
                        data-bs-toggle="modal" data-bs-target="#editData"
                        aria-label="Edit">
                        <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                    </button>
                    <button type="button" data-id="${row.id}" class="btn-action" aria-label="Hapus"
                        data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                    </button>
                `;
            }}
        ],
        order: [[2, 'asc']]
    });
});
</script>
@endsection
