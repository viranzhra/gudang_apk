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
    
    <table class="table table-bordered table-striped table-hover" id="statusbarangtable" width="100%">
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
                <h5 class="modal-title" id="tambahDataModalLabel">Add Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('statusbarang.store') }}" enctype="multipart/form-data">
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
                        <label for="nama" class="form-label">Item Status</label>
                        <input type="text" id="nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-warna" class="form-label">Warna</label>
                        <input type="color" id="edit-warna" name="warna" class="form-control" required />
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
                        <label for="edit-nama" class="form-label">Item Status</label>
                        <input type="text" id="edit-nama" name="nama" class="form-control" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit-warna" class="form-label">Warna</label>
                        <input type="color" id="edit-warna" name="warna" class="form-control" required />
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

{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> --}}

<script>
$(document).on('click', '.btn-edit', function() {
    const itemId = $(this).data('id');  // Get the ID of the item
    const itemName = $(this).data('nama');  // Get the item name

    // Update the form action URL with the correct item ID
    $('#editForm').attr('action', `/statusbarang/update/${itemId}`);

    // Populate form fields with the item data
    $('#edit-nama').val(itemName);

    // Show the modal
    $('#editData').modal('show');
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
$('#deleteModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget); // Button that triggered the modal
    const itemId = button.data('id'); // Extract info from data-* attributes
    const itemName = button.closest('tr').find('td:nth-child(3)').text();

    $('#itemName').text(itemName);
    $('#deleteForm').attr('action', `/statusbarang/delete/${itemId}`);
});
</script>

<!-- Script untuk inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        $('#statusbarangtable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: 'https://doaibutiri.my.id/gudang/api/statusbarang',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('token') }}'
                }
            },
            columns: [
            {
                data: 'id',
                orderable: false,
                render: function(data) {
                    return `<input type="checkbox" class="select-item" value="${data}">`;
                }
            },
            {
                data: null,
                sortable: false,
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'nama',
                render: function(data, type, row) {
                        return `<div class="flex items-center"><span class="rounded-circle border d-inline-block me-2" style="width: 1rem; height: 1rem;background-color:${row.warna||'#000000'}"></span>${data}</div>`;
                }
            },
            {
                data: 'id',
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <button type="button" class="btn-edit btn-action" 
                            data-id="${row.id}" 
                            data-nama="${row.nama}"
                            data-bs-toggle="modal" data-bs-target="#editData"
                            aria-label="Edit">
                            <iconify-icon icon="mdi:pencil" class="icon-edit"></iconify-icon>
                        </button>
                        <button type="button" data-id="${row.id}" class="btn-action" aria-label="Hapus"
                            data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <iconify-icon icon="mdi:delete" class="icon-delete"></iconify-icon>
                        </button>
                    `;
                }
            }
        ],
            order: [
                [2, 'asc']
            ]
        });

        // Handle select-all checkbox
        $(document).on('change', '#select-all', function() {
            const isChecked = $(this).is(':checked');
            $('.select-item').prop('checked', isChecked);
            toggleDeleteButton();
        });

        // Handle individual checkboxes
        $(document).on('change', '.select-item', function() {
            toggleDeleteButton();
        });

        // Function to toggle the delete button based on selection
        function toggleDeleteButton() {
            const selected = $('.select-item:checked').length;
            const deleteButton = $('#deleteSelected');
            if (selected > 0) {
                deleteButton.removeClass('d-none');
            } else {
                deleteButton.addClass('d-none');
            }
        }

        // Handle delete selected button click
        $(document).on('click', '#deleteSelected', function() {
            const selected = [];
            $('.select-item:checked').each(function() {
                selected.push($(this).val());
            });

            if (selected.length > 0) {
                if (confirm('Apakah Anda yakin ingin menghapus data yang dipilih?')) {
                    fetch('/statusbarang/deleteSelected', {
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
    });
</script>
@endsection
