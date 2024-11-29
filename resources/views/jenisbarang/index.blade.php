@extends('layouts.navigation')

@section('content')
<style>
    .btn-action {
        background: none;
        border: none; 
        padding: 0; 
        cursor: pointer; 
    }
    
    .icon-edit, .icon-delete {
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

    .icon-edit {
        background-color: #01578d;
    }

    .icon-delete {
        background-color: #910a0a;
    }

    #notification {
        position: fixed;
        top: 10px;
        right: 10px;
        width: 300px;
        padding: 15px;
        border-radius: 5px;
        z-index: 9999;
        display: none;
        text-align: left;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-info {
        background-color: #d1ecf1;
        color: #0c5460;
    }
</style>

<div class="container mt-3 rounded-4 shadow-sm" style="padding-bottom: 15px; padding-top: 10px; min-width: 1160px;">
    <!-- Notification Element -->
    <div id="notification" class="alert" style="display: none;">
        <strong id="notificationTitle">Notification</strong>
        <p id="notificationMessage"></p>
    </div>

    <div class="d-flex align-items-center justify-content-between pb-3 pt-2">
        <div class="d-flex align-items-center">
            <h4>Item Type</h4>
        </div>

        <div class="d-flex align-items-center gap-3 justify-content-end" style="padding-bottom: 10px">
            <!-- Add Button -->
            <a href="#" class="btn btn-primary d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#tambahDataModal" style="width: 75px; height: 35px;">
                <iconify-icon icon="mdi:plus-circle" style="font-size: 18px; margin-right: 5px;"></iconify-icon>
                Add
            </a>              
            <!-- Delete Selected Button -->
            <button id="deleteSelected" class="btn btn-danger d-none" style="background-color: #910a0a; border: none; height: 35px; display: flex; align-items: center; justify-content: center;">
                <iconify-icon icon="mdi:delete" style="font-size: 16px; margin-right: 5px;"></iconify-icon>
                Delete Selected
            </button>          
        </div>   
    </div> 

    <table class="table table-bordered table-striped table-hover" id="jenisBarangTable" width="100%">
        <thead class="thead-dark">
            <tr>
                <th style="width: 20px"><input type="checkbox" id="select-all"></th>
                <th style="width: 25px;">No</th>
                <th>Type Item</th>
                <th>Description</th>
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
                <form id="addForm" method="post" action="{{ route('jenisbarang.store') }}" enctype="multipart/form-data">
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
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Description</label>
                        <input type="text" id="deskripsi" name="deskripsi" class="form-control" />
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
                    <div class="mb-3">
                        <label for="edit-deskripsi" class="form-label">Description</label>
                        <input type="text" id="edit-deskripsi" name="deskripsi" class="form-control" />
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>                
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                Apakah Anda yakin ingin menghapus jenis barang <span id="itemName"></span> ini?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="background-color: #910a0a">Delete</button>
                </form>                               
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus Beberapa Data -->
<div class="modal fade" id="confirmDeleteSelectedModal" tabindex="-1" aria-labelledby="confirmDeleteSelectedLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteSelectedLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="color: black">
                Apakah Anda yakin ingin menghapus <span id="selectedCount"></span> jenis barang yang dipilih?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="background-color: #1ca13b; border: none; color:rgb(255, 255, 255)" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="confirmDeleteSelected" class="btn btn-danger" style="background-color: #910a0a">Hapus</button>
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

<!-- Script untuk inisialisasi DataTables -->
<script>
    $(document).ready(function() {
        // Inisialisasi DataTables
        const table = $('#jenisBarangTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '{{ env('API_URL') }}/jenisbarang',
                headers: {
                    'Authorization': 'Bearer ' + '{{ session('jwt_token') }}'
                }
            },
            order: [[2, 'asc']],
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
                    orderable: false,
                    className: 'text-center',
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    }
                },
                { data: 'nama' },
                { data: 'deskripsi' },
                {
                    data: null,
                    orderable: false,
                    render: function(data) {
                        return `
                            <button class="btn-action edit-btn" data-id="${data.id}" data-nama="${data.nama}" data-deskripsi="${data.deskripsi}">
                                <div class="icon-edit"><iconify-icon icon="mdi:pencil"></iconify-icon></div>
                            </button>
                            <button class="btn-action delete-btn" data-id="${data.id}" data-nama="${data.nama}">
                                <div class="icon-delete"><iconify-icon icon="mdi:delete"></iconify-icon></div>
                            </button>
                        `;
                    }
                }
            ],
        });        
        // Function to show notifications
        function showNotification(title, message, type) {
            const notification = $('#notification');
            $('#notificationTitle').text(title);
            $('#notificationMessage').text(message);
            notification.removeClass('alert-success alert-danger alert-info').addClass(`alert-${type}`);
            notification.fadeIn();
    
            // Hide notification after 5 seconds
            setTimeout(() => {
                notification.fadeOut();
            }, 5000);
        }

        // Handling the edit button click event
        $(document).on('click', '.edit-btn', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const deskripsi = $(this).data('deskripsi');
            $('#editForm').attr('action', `{{ env('API_URL') }}/jenisbarang/${id}`);
            $('#edit-nama').val(nama);
            $('#edit-deskripsi').val(deskripsi);
            $('#editData').modal('show');
        });

        // Handling the delete button click event
        $(document).on('click', '.delete-btn', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            $('#itemName').text(nama);
            $('#deleteForm').attr('action', `{{ env('API_URL') }}/jenisbarang/${id}`);
            $('#deleteModal').modal('show');
        });

                // Handle add form submission
                $('#tambahDataModal form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serialize();
    
            $.ajax({
                url: `{{ env('API_URL') }}/jenisbarang`,
                method: 'POST',
                data: formData,
                success: function(response) {
                    showNotification('Success', 'Data berhasil ditambahkan.', 'success');
                    $('#tambahDataModal').modal('hide');
                    $('#jenisBarangTable').DataTable().ajax.reload(); // Reload table data
                },
                error: function() {
                    showNotification('Error', 'Gagal menambahkan data.', 'danger');
                }
            });
        });

        // Handling form submission for editing
        $('#editForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            const formData = $(this).serialize();

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                success: function(response) {
                    $('#editData').modal('hide');
                    $('#notification').removeClass('alert-danger').addClass('alert-success').fadeIn();
                    $('#notificationTitle').text('Success');
                    $('#notificationMessage').text(response.message);
                    $('#notification').fadeOut(3000);
                    table.ajax.reload(); // Reload the DataTable
                },
                error: function(xhr) {
                    $('#notification').removeClass('alert-success').addClass('alert-danger').fadeIn();
                    $('#notificationTitle').text('Error');
                    $('#notificationMessage').text(xhr.responseJSON.message || 'Something went wrong!');
                    $('#notification').fadeOut(3000);
                }
            });
        });

        // Handle delete form submission
        $('#deleteForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: $(this).serialize(),
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    $('#notification').removeClass('alert-danger').addClass('alert-success').fadeIn();
                    $('#notificationTitle').text('Success');
                    $('#notificationMessage').text(response.message);
                    $('#notification').fadeOut(3000);
                    table.ajax.reload(); // Reload the DataTable
                },
                error: function(xhr) {
                    $('#notification').removeClass('alert-success').addClass('alert-danger').fadeIn();
                    $('#notificationTitle').text('Error');
                    $('#notificationMessage').text(xhr.responseJSON.message || 'Something went wrong!');
                    $('#notification').fadeOut(3000);
                }
            });
        });

         // Handle select all checkbox
        $('#select-all').on('click', function() {
            const isChecked = $(this).is(':checked');
            $('.select-item').prop('checked', isChecked);
            $('#deleteSelected').toggleClass('d-none', !isChecked);
        });

        // Toggle delete button visibility based on item selection
        $(document).on('change', '.select-item', function() {
            const anyChecked = $('.select-item:checked').length > 0;
            $('#deleteSelected').toggleClass('d-none', !anyChecked);
            $('#select-all').prop('checked', $('.select-item:checked').length === $('.select-item').length);
        });

        // Handle delete selected button
        $('#deleteSelected').on('click', function() {
            const selectedIds = $('.select-item:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length > 0) {
                $('#selectedCount').text(selectedIds.length);
                $('#confirmDeleteSelectedModal').modal('show');
            } else {
                showNotification('Warning', 'No items selected for deletion.', 'info');
            }
        });

        // Handle the actual deletion after confirmation
        $('#confirmDeleteSelected').on('click', function() {
            const selectedIds = $('.select-item:checked').map(function() {
                return $(this).val();
            }).get();

            if (selectedIds.length > 0) {
                $.ajax({
                    url: '{{ env('API_URL') }}/jenisbarang/delete-selected',
                    method: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        showNotification('Success', response.message, 'success');
                        table.ajax.reload();
                        $('#select-all').prop('checked', false);
                        $('#confirmDeleteSelectedModal').modal('hide');
                        $('#deleteSelected').addClass('d-none');
                    },
                    error: function(xhr) {
                        showNotification('Error', 'Failed to delete selected items: ' + xhr.responseJSON.message, 'danger');
                    }
                });
            }
        });
    });
</script>
@endsection

